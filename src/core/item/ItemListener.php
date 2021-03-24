<?php

declare(strict_types=1);

namespace core\item;

use core\crate\task\MonthlyCrateTask;
use core\event\EventManager;
use core\item\enchantment\Enchantment;
use core\item\task\HolyBoxAnimationTask;
use core\item\types\BossEgg;
use core\item\types\ChestKit;
use core\item\types\CrateKeyNote;
use core\item\types\Drops;
use core\item\types\boxes\MonthlyCrate;
use core\item\types\boxes\LootBag;
use core\item\types\HolyBox;
use core\item\types\MoneyNote;
use core\item\types\ShardsNote;
use core\item\types\moneypouches\CommonPouch;
use core\item\types\mobs\PhoenixBone;
use core\item\types\Artifact;
use core\item\types\SellWand;
use core\item\types\XPNote;
use core\faction\Faction;
use core\combat\boss\heroes\Zephyr;
use core\Urbis;
use core\CorePlayer;
use core\event\relics\CommonRewardChooser;
use core\event\relics\MythicRewardChooser;
use core\event\relics\LegendaryRewardChooser;
use core\item\types\boxes\BossChest;
use core\item\types\boxes\MaskBox;
use core\item\types\relics\CommonRelic;
use core\item\types\relics\LegendaryRelic;
use core\item\types\relics\MythicRelic;
use core\item\types\relics\RareRelic;
use core\item\types\features\RankShard;
use core\item\types\raiding\TNTLauncher;
use core\item\types\others\WallGenerator;
use core\item\types\notes\BloodyNote;
use core\price\event\ItemSellEvent;
use core\translation\Translation;
use core\translation\TranslationException;
use core\utils\UtilsException;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\level\sound\AnvilBreakSound;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\scheduler\Task;
use pocketmine\tile\Chest as TileChest;
use pocketmine\tile\Container;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerItemConsumeEvent;

class ItemListener implements Listener
{

    /** @var Urbis */
    private $core;

    /** @var array */
    private $ids = [
        Block::COAL_ORE,
        Block::DIAMOND_ORE,
        Block::EMERALD_ORE,
        Block::REDSTONE_ORE,
        Block::LAPIS_ORE,
        Block::NETHER_QUARTZ_ORE
    ];

    /** @var int */
    private $sellWandCooldown = [];

    /**
     * ItemListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core)
    {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        if ($player->getArmorInventory() !== null)
            $player->setActiveArmorEnchantments();
    }

    /**
     * @priority LOWEST
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        $name = TextFormat::RESET . TextFormat::WHITE . $item->getName();
        if ($item->hasCustomName()) {
            $name = $item->getCustomName();
        }
        $replace = TextFormat::DARK_GRAY . "[" . $name . TextFormat::RESET . TextFormat::GRAY . " * " . TextFormat::WHITE . $item->getCount() . TextFormat::DARK_GRAY . "]" . TextFormat::RESET . $player->getRank()->getChatColor();
        $message = $event->getMessage();
        $message = str_replace("[item]", $replace, $message);
        $event->setMessage($message);
    }

    /**
     * @priority NORMAL
     * @param PlayerItemHeldEvent $event
     */
    public function onPlayerItemHeld(PlayerItemHeldEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $item = $event->getItem();
        if ($item->hasEnchantments()) {
            if ($item->hasEnchantment(Enchantment::KNOCKBACK)) {
                $player->getInventory()->removeItem($item);
                $item->removeEnchantment(Enchantment::KNOCKBACK);
                $player->getInventory()->addItem($item);
           }    
        }
    }

    /**
     * @priority HIGHEST
     * @param PlayerInteractEvent $event
     *
     * @throws TranslationException
     * @throws UtilsException
     */
    public function onPlayerInteract(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $inventory = $player->getInventory();
        if ($item->getId() === Item::EXPERIENCE_BOTTLE) {
            $xp = 0;
            for ($i = 0; $i <= $item->getCount(); ++$i) {
                $xp += mt_rand(6, 18);
            }
            $player->addXp($xp);
            $inventory->removeItem($item);
            $event->setCancelled();
            return;
        }
        $tag = $item->getNamedTagEntry(CustomItem::CUSTOM);
        if ($tag === null) {
            return;
        }
        if ($tag instanceof CompoundTag) {
            if ($tag->hasTag(CrateKeyNote::CRATE, StringTag::class) and $tag->hasTag(CrateKeyNote::AMOUNT, IntTag::class)) {
                $crate = $tag->getString(CrateKeyNote::CRATE);
                $amount = $tag->getInt(CrateKeyNote::AMOUNT);
                $crate = $this->core->getCrateManager()->getCrate($crate);
                $player->addKeys($crate, $amount);
                $player->sendMessage("§7You've successfully claimed your §a" . $amount . " §2" . $crate->getName() . " §acrate key(s)!§r");
                $player->playXpLevelUpSound();
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(ChestKit::KIT, StringTag::class)) {
                $kit = $tag->getString(ChestKit::KIT);
                $kit = $this->core->getKitManager()->getKitByName($kit);
                if ($kit->giveTo($player)) {
                    $player->getLevel()->addSound(new AnvilBreakSound($player));
                    $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                }
                $event->setCancelled();
            }
            if ($tag->hasTag(XPNote::XP, IntTag::class)) {
                $amount = $tag->getInt(XPNote::XP);
                $player->sendMessage("§a+" . $amount . " §aXP ");
                $player->playXpLevelUpSound();
                $player->addXp($amount);
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(MoneyNote::BALANCE, IntTag::class)) {
                $amount = $tag->getInt(MoneyNote::BALANCE);
                $player->sendMessage("§a+ §6$" . $amount . " §r");
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $player->addToBalance($amount);
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(ShardsNote::SHARDS, IntTag::class)) {
                $amount = $tag->getInt(ShardsNote::SHARDS);
                $player->sendMessage("§a+ §3" . $amount . " §r");
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $player->addShards($amount);
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(Artifact::ARTIFACT, StringTag::class)) {
				if(mt_rand(1, 5) == 1){
					$kits = $this->core->getKitManager()->getGodlyKits();
                    $kit = $kits[array_rand($kits)];
                    $player->getLevel()->addSound(new BlazeShootSound($player));
					$player->getInventory()->addItem((new HolyBox($kit))->getItemForm());
					$player->sendTitle("§r§aLUCKY!", "§7You have gotten a Meta Box!");
				} else {
					$player->getLevel()->addSound(new AnvilUseSound($player));
					$player->addToBalance(mt_rand(10000, 50000));
					$player->sendTitle("§r§cUNLUCKY!", "§7instead you recieved amount of $10-$50K");
				}
				$inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if($tag->hasTag(TNTLauncher::TNTLauncher, StringTag::class)) {

                if($player->getLevel()->getFolderName() !== Faction::CLAIM_WORLD) {
                    $player->sendMessage("Your not in a proper world to do this.");
                    return;
                }

                $uses = $tag->getInt(TNTLauncher::TLUses);
                $tier = $tag->getInt(TNTLauncher::TLTier);
                $requiredTnt = $tag->getInt(TNTLauncher::TNTReq);

                $tntItem = Item::get(Item::TNT);

                if($player->getInventory()->contains($tntItem)) {

                    $tnt = Entity::createEntity("PrimedTNT", $player->getLevel(), Entity::createBaseNBT($player));
                    $tnt->setMotion($player->getDirectionVector()->normalize()->multiply($tier));
                    $tnt->spawnToAll();

                    $inventory->removeItem($tntItem);

                    --$uses;

                    if($uses <= 0) {

                        $player->getLevel()->addSound(new AnvilBreakSound($player));
                        $inventory->setItemInHand($item->setCount($item->getCount() - 1));

                    } else {

                        $tag->setInt(TNTLauncher::TLUses, $uses);

                        $lore = [];
                        $lore[] = "";
                        $lore[] = "";
                        $lore[] = "§eThe greater the tier, the larger radius and tnt required.";
                        $lore[] = "";
                        $lore[] = "§r§cUses: §e$uses";
                        $lore[] = "§r§cTier: §e$tier";
                        $lore[] = "";
                        $lore[] = "§r§7Each fire will require §e$requiredTnt §7TNT!";
                        $item->setLore($lore);
                        $inventory->setItemInHand($item);

                    }

                    $event->setCancelled(true);

                } else {
                    $player->sendMessage("You must have atleast $requiredTnt TNT");
                    return;
                }

            }
            if ($tag->hasTag(CommonPouch::COMMONPOUCH, StringTag::class)) {
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $amts = (mt_rand(10000, 80000));
                $player->addToBalance($amts);
                $player->sendMessage("§dYou uncovered your Money Pouch and recieved §l§d$" . number_format($amts));
                $player->sendTitle("§r §a+" . number_format($amts) . " ", "§r§fYou have used your Common Money Pouch!");
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(BloodyNote::BLOODYNOTE, StringTag::class)) {
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $amts = (mt_rand(2500, 8000));
                $player->addToBalance($amts);
                $player->sendMessage("§aYou have used a bloody note and recieved §a§l$" . number_format($amts));
                $player->sendTitle("§r §a+" . number_format($amts) . "", "§r§fYou have used a Bloody Note!");
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
            if ($tag->hasTag(LegendaryRelic::LEGENDARYRELIC, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getLegendaryChooser()->getReward();
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(MythicRelic::MYTHICRELIC, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getMythicChooser()->getReward();
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(CommonRelic::COMMONRELIC, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getCommonChooser()->getReward();
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(RareRelic::RARERELIC, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getRareChooser()->getReward();
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(RankShard::RANKSHARD, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getRankChooser()->getReward();
                $player->playXpLevelUpSound();
                $player->addTitle("§l§aYOUR RANK IS NOW", "§7" . $reward->getName());
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(BossChest::BOSSCHEST, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getBossChooser()->getReward();
                $player->addTitle("§cYOU OPENED","§7Boss Chest");
                $player->getLevel()->addSound(new AnvilUseSound($player));
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(MaskBox::MASKBOX, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getCharmChooser()->getReward();
                $player->getLevel()->addSound(new BlazeShootSound($player));
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(LootBag::LOOTBAG, StringTag::class)) {
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $reward = EventManager::getLootBagChooser()->getReward();
                $player->sendMessage("§l§6[!]§r §8>> §7You have opened a lootbag and recieved rewards!");
                $player->playXpLevelUpSound();
                $callable = $reward->getCallback();
                $callable($player);
                $event->setCancelled();
            }
            if ($tag->hasTag(BossEgg::BOSS_ID, IntTag::class)) {
                if ($player->getLevel()->getFolderName() !== "BossArena") {
                    $player->sendMessage(Translation::getMessage("canOnlySpawnInArena"));
                    return;
                }
                $areaManager = $this->core->getAreaManager();
                $areas = $areaManager->getAreasInPosition($player->asPosition());
                if ($areas !== null) {
                    foreach ($areas as $area) {
                        if ($area->getPvpFlag() === false) {
                            $player->sendMessage(Translation::getMessage("canOnlySpawnInArena"));
                            return;
                        }
                    }
                }
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                $this->core->getCombatManager()->createBoss($tag->getInt(BossEgg::BOSS_ID), $player->getLevel(), Entity::createBaseNBT($player->asPosition()));
                $this->core->getServer()->broadcastMessage(Translation::getMessage("bossSpawned"));
                $event->setCancelled();
            }
            if ($tag->hasTag(SellWand::USES, IntTag::class)) {
                if (isset($this->sellWandCooldown[$player->getRawUniqueId()]) and (time() - $this->sellWandCooldown[$player->getRawUniqueId()]) < 3) {
                    $seconds = 3 - (time() - $this->sellWandCooldown[$player->getRawUniqueId()]);
                    $player->sendMessage(Translation::getMessage("actionCooldown", [
                        "amount" => TextFormat::RED . $seconds
                    ]));
                    return;
                }
                if ($event->isCancelled()) {
                    $player->sendMessage(Translation::getMessage("blockProtected"));
                    return;
                }
                $tile = $block->getLevel()->getTile($block);
                if (!$tile instanceof Container) {
                    $player->sendMessage(Translation::getMessage("invalidBlock"));
                    return;
                }
                $content = $tile->getInventory()->getContents();
                /** @var Item[] $items */
                $items = [];
                $sellable = false;
                $sellables = $this->core->getPriceManager()->getSellables();
                $entries = [];
                foreach ($content as $i) {
                    if (!isset($sellables[$i->getId()])) {
                        continue;
                    }
                    $entry = $sellables[$i->getId()];
                    if (!$entry->equal($i)) {
                        continue;
                    }
                    if ($sellable === false) {
                        $sellable = true;
                    }
                    if (!isset($entries[$entry->getName()])) {
                        $entries[$entry->getName()] = $entry;
                        $items[$entry->getName()] = $i;
                    } else {
                        $items[$entry->getName()]->setCount($items[$entry->getName()]->getCount() + $i->getCount());
                    }
                }
                if ($sellable === false) {
                    $event->setCancelled();
                    $player->sendMessage(Translation::getMessage("nothingSellable"));
                    $this->sellWandCooldown[$player->getRawUniqueId()] = time();
                    return;
                }
                $price = 0;
                foreach ($entries as $entry) {
                    $i = $items[$entry->getName()];
                    $price += $i->getCount() * $entry->getSellPrice();
                    $tile->getInventory()->removeItem($i);
                    $ev = new ItemSellEvent($player, $i, $price);
                    $ev->call();
                    $player->sendMessage(Translation::getMessage("sell", [
                        "amount" => TextFormat::GREEN . $i->getCount(),
                        "item" => TextFormat::DARK_GREEN . $entry->getName(),
                        "price" => TextFormat::LIGHT_PURPLE . "$" . $price
                    ]));
                }
                $player->addToBalance($price);
                $amount = $tag->getInt(SellWand::USES);
                $player->playXpLevelUpSound();
                --$amount;
                if ($amount <= 0) {
                    $player->getLevel()->addSound(new AnvilBreakSound($player));
                    $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                } else {
                    $tag->setInt(SellWand::USES, $amount);
                    $lore = [];
                    $lore[] = "";
                    $lore[] = TextFormat::RESET . TextFormat::AQUA . "Uses: " . TextFormat::WHITE . $amount;
                    $lore[] = "";
                    $lore[] = TextFormat::RESET . TextFormat::WHITE . "Tap a chest to sell all It's sellable contents.";
                    $item->setLore($lore);
                    $inventory->setItemInHand($item);
                }
                $event->setCancelled();
                $this->sellWandCooldown[$player->getRawUniqueId()] = time();
            }
            if ($tag->hasTag(HolyBox::SACRED_KIT, StringTag::class)) {
                $event->setCancelled();
                if ($player->getLevel()->getFolderName() !== $this->core->getServer()->getDefaultLevel()->getFolderName()) {
                    $player->sendMessage(Translation::getMessage("onlyInSpawn"));
                    return;
                }
                if ($block->getId() !== Block::AIR) {
                    $position = Position::fromObject($block->add(0, 1, 0), $player->getLevel());
                    if ($player->getLevel()->getBlock($position)->getId() === Block::AIR) {
                        $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                        $faces = [
                            0 => 4,
                            1 => 2,
                            2 => 5,
                            3 => 3
                        ];
                        $face = $faces[$player->getDirection()];
                        $position->getLevel()->setBlock($position, Block::get(Block::CHEST, $face));
                        $this->core->getScheduler()->scheduleRepeatingTask(new HolyBoxAnimationTask($player, $position, $this->core->getKitManager()->getKitByName($tag->getString(HolyBox::SACRED_KIT))), 7);
                    }
                }
            }
            if ($tag->hasTag(Drops::ITEM_LIST, ListTag::class)) {
                $list = $tag->getListTag(Drops::ITEM_LIST);
                $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                foreach ($list->getAllValues() as $tag) {
                    $item = Item::nbtDeserialize($tag);
                    if ($inventory->canAddItem($item)) {
                        $inventory->addItem($item);
                    }
                }
                $player->getLevel()->addSound(new BlazeShootSound($player->asPosition()));
                $event->setCancelled();
            }
            if ($tag->hasTag(PhoenixBone::PHOENIX_BONE, StringTag::class)) {
                $itemName = ($item->getCustomName() !== "") ? $item->getCustomName() : $item->getName();
                if(isset(Urbis::$instance->getItemManager()->itemCoolDown[$player->getName()][$itemName])){
                    if(time() < time() - Urbis::$instance->getItemManager()->itemCoolDown[$player->getName()][$itemName]){
                        $timeFormat = gmdate("H:i:s", time() - Urbis::$instance->getItemManager()->itemCoolDown[$player->getName()][$itemName]);
                        $player->sendMessage(Translation::RED."This action is on cool down for $timeFormat");
                        return;
                    }
                }
                if($player->getLevel()->getName() !== Faction::CLAIM_WORLD){
                    $player->sendMessage(Translation::getMessage("canOnlySpawnInArena"));
                    return;
                }

                if ($block->getId() !== Block::AIR) {
                    $inventory->setItemInHand($item->setCount($item->getCount() - 1));

                    if (!$player->getLevel()->isChunkLoaded($block->x >> 4, $block->z >> 4)) {
                        $player->getLevel()->loadChunk($block->x >> 4, $block->z >> 4, true);
                    }
                    $phoenix = Entity::createEntity("Phoenix", $player->getLevel(), Entity::createBaseNBT($block));
                    $phoenix->spawnToAll();
                    // $event->setCancelled();
                }

                Urbis::$instance->getItemManager()->itemCoolDown[$player->getName()][$itemName] = time() + 60 * 5; //5minutes
            }
            if ($tag->hasTag(MonthlyCrate::MONTHLY_CRATE, StringTag::class)) {
                $event->setCancelled(true);
                if ($block->getId() !== Block::AIR) {
                    $position = Position::fromObject($block->add(0, 1, 0), $player->getLevel());
                    if ($player->getLevel()->getBlock($position)->getId() === Block::AIR) {
                        $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                        $faces = [
                            0 => 4,
                            1 => 2,
                            2 => 5,
                            3 => 3
                        ];
                        $face = $faces[$player->getDirection()];
                        $position->getLevel()->setBlock($position, Block::get(Block::CHEST, $face));
                        $nbt = TileChest::createNBT($position, $face, $item, $player);
                        $tile = Tile::createTile(Tile::CHEST, $player->getLevelNonNull(), $nbt);
                        $slot = 0;
                        if ($tile instanceof TileChest) {
                            foreach (Urbis::getInstance()->getCrateManager()->getMonthlyCrate()->getRewards() as $reward) {
                                $tile->getInventory()->setItem($slot, $reward->getItem()->setCustomName($reward->getName()));
                                ++$slot;
                            }
                            new MonthlyCrateTask($player, $position);
                        }
                    }
                }
            }
        }
    }
    /**
     * @priority LOWEST
     * @param BlockBreakEvent $event
     */
    public function onBlockBreak(BlockBreakEvent $event): void
    {
        if ($event->isCancelled()) {
            return;
        }
        $item = $event->getItem();
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $blockId = $block->getId();
        if (($level = $item->getEnchantmentLevel(Enchantment::FORTUNE)) > 0) {
            if (!in_array($blockId, $this->ids)) {
                return;
            }
            $id = 0;
            switch ($blockId) {
                case Block::COAL_ORE:
                    $id = Item::COAL;
                    break;
                case Block::DIAMOND_ORE:
                    $id = Item::DIAMOND;
                    break;
                case Block::EMERALD_ORE:
                    $id = Item::EMERALD;
                    break;
                case Block::REDSTONE_ORE:
                    $id = Item::REDSTONE;
                    break;
                case Block::LAPIS_ORE:
                    $id = Item::DYE;
                    break;
                case Block::NETHER_QUARTZ_ORE:
                    $id = Item::NETHER_QUARTZ;
                    break;
            }
            $item = Item::get($id, 0, 1 + mt_rand(0, $level + 2));
            if ($item->getId() === Item::DYE) {
                $item->setDamage(4);
                $item->setCount(2 + mt_rand(0, $level + 2) * 2);
            }
            $drops = [$item];
            $event->setDrops($drops);
        }
    }

    /**
     * @priority HIGHEST
     * @param EntityDamageEvent $event
     */
    public function onEntityDamage(EntityDamageEvent $event): void
    {
        if ($event->isCancelled()) {
            return;
        }
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if (!$damager instanceof CorePlayer) {
                return;
            }
            $item = $damager->getInventory()->getItemInHand();
            if (($level = $item->getEnchantmentLevel(Enchantment::LOOTING)) <= 0) {
                return;
            }
            /** @var Living $entity */
            $entity = $event->getEntity();
            if ($entity instanceof CorePlayer) {
                return;
            }
            if ($event->getFinalDamage() >= $entity->getHealth()) {
                foreach ($entity->getDrops() as $drop) {
                    $drop->setCount($drop->getCount() + mt_rand(1, $level));
                    $entity->getLevel()->dropItem($entity, $drop);
                }
            }
        }
    }

    /**
     * @priority HIGHEST
     * @param InventoryTransactionEvent $event
     */
    public function onInventoryTransaction(InventoryTransactionEvent $event)
    {
        $transaction = $event->getTransaction();
        foreach ($transaction->getActions() as $action) {
            if ($action instanceof SlotChangeAction) {
                $inventory = $action->getInventory();
                if ($action->getSourceItem()->hasEnchantments() or $action->getTargetItem()->hasEnchantments()) {
                    if ($inventory instanceof ArmorInventory) {
                        $holder = $inventory->getHolder();
                        if ($holder instanceof CorePlayer) {
                            $this->core->getScheduler()->scheduleDelayedTask(new class($holder) extends Task
                            {

                                /** @var CorePlayer */
                                private $player;

                                /**
                                 *  constructor.
                                 *
                                 * @param CorePlayer $player
                                 */
                                public function __construct(CorePlayer $player)
                                {
                                    $this->player = $player;
                                }

                                /**
                                 * @param int $currentTick
                                 */
                                public function onRun(int $currentTick)
                                {
                                    if ($this->player->isOnline()) {
                                        $this->player->setActiveArmorEnchantments();
                                    }
                                }
                            }, 1);
                        }
                        return;
                    }
                }
            }
        }
    }
}
