<?php

declare(strict_types=1);

namespace core;
use core\discord\DiscordManager;
use core\faction\Faction;
use core\CorePlayer;
use core\rank\Rank;
use core\task\PlayerKickTask;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\block\Block;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\level\Position;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\item\Item;
use pocketmine\level\particle\Particle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\ItemSpawnEvent;
class UrbisListener implements Listener
{
    private $core;
    private $count = 0;
    private $messages = [];
    private $chat = [];
    private $command = [];
    public $skin = [];

	protected $foodCount = 0;

	protected $foodEvent = false;

	protected $foodMax = 150;

    public function __construct(Urbis $core)
    {
        $this->core = $core;
        $this->messages[] = Urbis::$SERVER_NAME;
        $this->messages = array_merge($this->messages, Urbis::MESSAGES);
    }

    public function onCommandUse(CommandEvent $e): void
    {
        /** @var CorePlayer $player */
        $player = $e->getSender();
        if ($player instanceof CorePlayer) {
            if ($player->getRank()->getIdentifier() >= 8 and !in_array($player->getRank()->getIdentifier(), [Rank::GLORIOUS, Rank::YOUTUBER, Rank::FAMOUS])) {
                $webhook = "https://discord.com/api/webhooks/800863535374401598/Zzf1S6WqopW8V-aoDaZR321sw9YvXWETJ0IP44WgPPiuqI99UyzWvyBW3xsufKEpydEG";
                DiscordManager::postWebhook($webhook, "/" . $e->getCommand(), $player->getName());
            }
        }
    }

	public function onConsume(PlayerItemConsumeEvent $event){

		$player = $event->getPlayer();

		$item = $event->getItem();



		$webhook = "776157752283365447/ZVpNT5IW26cRotnpIyspJqGP0tSKH-PliBJrWBdCs9xuE1jOwa7HSdwIBNMaEp2HdF9T";



		if($item->getId() === 364){

			if($this->foodEvent == true){

				if($player->getFood() !== $player->getMaxFood()){

					$this->foodCount++;

				}

			}

			if($this->foodEvent == false){

				$this->foodEvent = true;

				$player->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::DARK_GRAY . "\n(" . TextFormat::AQUA . "!" . TextFormat::DARK_GRAY . ") " . TextFormat::RESET . TextFormat::GRAY . "The food event has started. Eat as much food as you can.\n\n");



				//DiscordManager::postWebhook($webhook, "@Events", "Astrobaut", [

					//[

						//"color" => 0x00ffff,

						//"title" => "THE FOOD EVENT HAS BEGUN",

						//"description" => "Hello there fellow astronauts! The food event has just begun!\nGet your tummies ready to rumble and start eating!\n\nJoin now with the following information:\n\n**IP**: play.astralmc.tk\n**PORT**: 19132 (Default)"

					//]

				//]);



			//}

			if($this->foodEvent == true and $this->foodCount == 100){

				$player->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::DARK_GRAY . "(" . TextFormat::AQUA . "!" . TextFormat::DARK_GRAY . ") " . TextFormat::RESET . TextFormat::GRAY . "Everyone online has eaten " . TextFormat::AQUA . $this->foodCount . TextFormat::GRAY . " food so far since the food event started! " . TextFormat::AQUA . "50" . TextFormat::GRAY . " food left to eat!");

			}

			if($this->foodEvent == true and $this->foodCount == $this->foodMax){

				$player->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::DARK_GRAY . "(" . TextFormat::AQUA . "!" . TextFormat::DARK_GRAY . ") " . TextFormat::RESET . TextFormat::GRAY . "Everyone online has eaten " . TextFormat::AQUA . $this->foodCount . TextFormat::GRAY . " food so far since the food event started! " . TextFormat::AQUA . "Event over.");

				$this->foodEvent = false;

				$this->foodCount = 0;

				$randomKeyAmount = mt_rand(1, 2);

				$player->getServer()->dispatchCommand(new ConsoleCommandSender(), "keyall Legendary $randomKeyAmount");

				$randomArtifact = mt_rand(1, 5);

			}

		}

	}
}

    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $player->load($this->core);
    }

    public function onQuit(PlayerQuitEvent $e): void
    {
        $p = $e->getPlayer();
        if ($p instanceof CorePlayer)
            $session = Urbis::getInstance()->getSession();
        if (isset($session[$p->getName()])) unset(Urbis::getInstance()->sessions[$p->getName()]);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $event->setJoinMessage("");
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $server = $this->core->getServer();
        $players = count($server->getOnlinePlayers());
        $maxPlayers = $this->core->getServer()->getMaxPlayers();
        $max = $maxPlayers - Urbis::EXTRA_SLOTS;

//        if ($players >= $max) {
//            if (!$player->hasPermission("permission.join.full") or $player->getRank()->getIdentifier() < Rank::TRAINEE) {
//                $this->core->getScheduler()->scheduleDelayedTask(new PlayerKickTask($player), 40);
//                return;
//            }
//        }
        foreach ($this->core->getServer()->getOnlinePlayers() as $onlinePlayer) {
            if ($player->getRank()->getIdentifier() >= Rank::TRAINEE and $player->getRank()->getIdentifier() <= Rank::OWNER) {
                break;
            }
            if ($onlinePlayer->hasVanished()) {
                $player->hidePlayer($onlinePlayer);
            }
        }
        if ($player->getCurrentTotalXp() > 0x7fffffff) {
            $player->setCurrentTotalXp(0x7fffffff);
        }
        if ($player->getCurrentTotalXp() < -0x80000000) {
            $player->setCurrentTotalXp(0);
        }
        $this->core->getScheduler()->scheduleDelayedTask(new class($player) extends Task
        {
            private $player;

            public function __construct(CorePlayer $player)
            {
                $this->player = $player;
            }

            public function onRun(int $currentTick)
            {
                if ($this->player->isOnline() === false) {
                    return;
                }
                $this->player->sendTitle("  ", "§6URBIS§r\n§7OP Factions" . "\n\n\n\n\n\n\n", 5, 20, 5);
            }
        }, 40);
        $this->skin[$player->getName()] = $player->getName();
    }

    public function onThrow(ItemSpawnEvent $e)
    {
        $entity = $e->getEntity();
        $item = $entity->getItem();
        $name = $item->getName();
        $count = $item->getCount();
        $entity->setNameTag("§7" . $name . " §r§ex" . $count . "§r");
        $entity->setNameTagVisible(true);
        $entity->setNameTagAlwaysVisible(true);
    }

    public function onPlayerExperienceChange(PlayerExperienceChangeEvent $event): void
    {
        $player = $event->getEntity();
        if (!$player instanceof CorePlayer) {
            return;
        }
        if ($player->getCurrentTotalXp() > 0x7fffffff or $player->getCurrentTotalXp() < -0x80000000) {
            $event->setCancelled();
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $event->setQuitMessage("");

        unset($this->skin[$event->getPlayer()->getName()]);
    }

    public function onPlayerCreation(PlayerCreationEvent $event): void
    {
        $event->setPlayerClass(CorePlayer::class);
    }

	public function regenerativeClassEffects(PlayerMoveEvent $event)

	{



		$player = $event->getPlayer();



		$player->applyClass();



	}

    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        if ($player->getRank()->getIdentifier() >= Rank::GLORIOUS) {
            return;
        }
        if ($player->getRank()->getIdentifier() < 13) {
            if (!isset($this->chat[$player->getRawUniqueId()])) {
                $this->chat[$player->getRawUniqueId()] = time();
                return;
            }
            if (time() - $this->chat[$player->getRawUniqueId()] >= 3) {
                $this->chat[$player->getRawUniqueId()] = time();
                return;
            }
            $seconds = 3 - (time() - $this->chat[$player->getRawUniqueId()]);
            $player->sendMessage(Translation::getMessage("actionCooldown", [
                "amount" => TextFormat::RED . $seconds
            ]));
            $event->setCancelled();
        }
    }

    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        if ($this->core->getAnnouncementManager()->getRestarter()->getRestartProgress() > 5) {
            if (strpos($event->getMessage(), "/") !== 0) {
                return;
            }
            if ($player->getRank()->getIdentifier() > 8) {
                return;
            }
            if (!isset($this->command[$player->getRawUniqueId()])) {
                $this->command[$player->getRawUniqueId()] = time();
                return;
            }
            if (time() - $this->command[$player->getRawUniqueId()] >= 3) {
                $this->command[$player->getRawUniqueId()] = time();
                return;
            }
            $seconds = 3 - (time() - $this->command[$player->getRawUniqueId()]);
            $player->sendMessage(Translation::getMessage("actionCooldown", [
                "amount" => TextFormat::RED . $seconds
            ]));
            $event->setCancelled();
            return;
        }
        $event->setCancelled();
        $player->sendMessage(Translation::getMessage("restartingSoon"));
    }

    public function onPlayerMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $level = $player->getLevel();
        if ($level->getName() !== Faction::CLAIM_WORLD) {
            return;
        }
        $x = abs($player->getFloorX());
        $y = abs($player->getFloorY());
        $z = abs($player->getFloorZ());
        $message = Translation::getMessage("borderReached");
        if ($x >= Urbis::BORDER) {
            $player->teleport(new Vector3($x - 1, $y, Urbis::BORDER - 1));
            $player->sendMessage($message);
        }
        if ($z >= Urbis::BORDER) {
            $player->teleport(new Vector3($x, $y, Urbis::BORDER - 1));
            $player->sendMessage($message);
        }
        if ($x >= Urbis::BORDER and abs($z) >= Urbis::BORDER) {
            $player->teleport(new Vector3(Urbis::BORDER - 1, $y, Urbis::BORDER - 1));
            $player->sendMessage($message);
        }
    }

    public function onQueryRegenerate(QueryRegenerateEvent $event): void
    {
        $this->core->getServer()->getNetwork()->setName($this->messages[$this->count++ % count($this->messages)]);
        $maxPlayers = $this->core->getServer()->getMaxPlayers();
        $maxSlots = $maxPlayers - Urbis::EXTRA_SLOTS;
        $players = count($this->core->getServer()->getOnlinePlayers());
        if ($players === $maxPlayers) {
            $event->setMaxPlayerCount($maxPlayers);
            return;
        }
        if ($maxSlots <= $players) {
            if ($players === $maxSlots) {
                $event->setMaxPlayerCount($maxSlots + 1);
                return;
            }
            $event->setMaxPlayerCount($maxSlots + $players + 1);
            return;
        }
        $event->setMaxPlayerCount($maxSlots);
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
            if ($player->isInStaffMode()) {
                $event->setCancelled();
                return;
            }
            if ($player->canAutoSell() && $player->isAutoSelling()) {
                $player->autoSell();
            }
            if ($player->isOp()) {
                return;
            }
            $level = $event->getBlock()->getLevel();
            if ($level->getName() !== Faction::CLAIM_WORLD) {
                $event->setCancelled();
                return;
            }
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
            if ($player->isInStaffMode()) {
                $event->setCancelled();
                return;
            }
            if ($player->isOp()) {
                return;
            }
            $level = $event->getBlock()->getLevel();
            if ($level->getName() !== Faction::CLAIM_WORLD) {
                $event->setCancelled();
                return;
            }
        }
    }

    public function onEntityLevelChange(EntityLevelChangeEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof CorePlayer) {
            return;
        }

        if($entity->getLevel()->getName() == $entity->getServer()->getDefaultLevel()->getName()){

            foreach ($entity->getFloatingTexts() as $floatingText) {
                $floatingText->spawn($entity);
            }
            foreach ($this->core->getEntityManager()->getNPCs() as $npc) {
                $npc->spawnTo($entity);
            }
        }
    }

    public function onLeaveDecay(LeavesDecayEvent $event): void
    {
        $event->setCancelled();
    }

    public function onArmorInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR) {
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        if ($player->getArmorInventory()->getHelmet()->getId() === Item::AIR && in_array($item->getId(), [Item::LEATHER_CAP, Item::CHAIN_HELMET, Item::IRON_HELMET, Item::GOLD_BOOTS, Item::DIAMOND_HELMET, Item::MOB_HEAD, Item::TURTLE_HELMET])) {
            if ($player->isCreative()) return;
            if ($event->isCancelled()) return;
            $helmet = Item::get($item->getId(), $item->getDamage());
            foreach ($item->getEnchantments() as $enchantment) {
                $helmet->addEnchantment($enchantment);
            }
            $helmet->setCustomName($item->hasCustomName() ? $item->getCustomName() : $item->getName());
            $helmet->setLore($item->getLore());
            $player->getArmorInventory()->setHelmet($helmet);
            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
        } elseif ($player->getArmorInventory()->getChestplate()->getId() === Item::AIR && in_array($item->getId(), [Item::LEATHER_CHESTPLATE, Item::CHAIN_CHESTPLATE, Item::IRON_CHESTPLATE, Item::GOLD_CHESTPLATE, Item::DIAMOND_CHESTPLATE, Item::ELYTRA])) {
            if ($player->isCreative()) return;
            if ($event->isCancelled()) return;
            $chestplate = Item::get($item->getId(), $item->getDamage());
            foreach ($item->getEnchantments() as $enchantment) {
                $chestplate->addEnchantment($enchantment);
            }
            $chestplate->setCustomName($item->hasCustomName() ? $item->getCustomName() : $item->getName());
            $chestplate->setLore($item->getLore());
            $player->getArmorInventory()->setChestplate($chestplate);
            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
        } elseif ($player->getArmorInventory()->getLeggings()->getId() === Item::AIR && in_array($item->getId(), [Item::LEATHER_LEGGINGS, Item::CHAIN_LEGGINGS, Item::IRON_LEGGINGS, Item::GOLD_LEGGINGS, Item::DIAMOND_LEGGINGS])) {
            if ($player->isCreative()) return;
            if ($event->isCancelled()) return;
            $leggings = Item::get($item->getId(), $item->getDamage());
            foreach ($item->getEnchantments() as $enchantment) {
                $leggings->addEnchantment($enchantment);
            }
            $leggings->setCustomName($item->hasCustomName() ? $item->getCustomName() : $item->getName());
            $leggings->setLore($item->getLore());
            $player->getArmorInventory()->setLeggings($leggings);
            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
        } elseif ($player->getArmorInventory()->getBoots()->getId() === Item::AIR && in_array($item->getId(), [Item::LEATHER_BOOTS, Item::CHAIN_BOOTS, Item::IRON_BOOTS, Item::GOLD_BOOTS, Item::DIAMOND_BOOTS])) {
            if ($player->isCreative()) return;
            if ($event->isCancelled()) return;
            $boots = Item::get($item->getId(), $item->getDamage());
            foreach ($item->getEnchantments() as $enchantment) {
                $boots->addEnchantment($enchantment);
            }
            $boots->setCustomName($item->hasCustomName() ? $item->getCustomName() : $item->getName());
            $boots->setLore($item->getLore());
            $player->getArmorInventory()->setBoots($boots);
            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
        }
    }

    public function onPlaceHead(BlockPlaceEvent $e): void
    {
        if ($e->getItem()->getId() == Item::SKULL) {
            $e->setCancelled();
        }
    }

    public function onInvTransaction(InventoryTransactionEvent $event): void
    {
        $player = $event->getTransaction()->getSource();
        if ($player instanceof CorePlayer) {
            if ($player->isInStaffMode()) {
                $event->setCancelled();
                return;
            }
        }
    }

    public function onItemDrop(PlayerDropItemEvent $event): void
    {
        $player = $event->getPlayer();
            if ($player->isInStaffMode()) {
                $event->setCancelled();
        }
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
            if ($player->isInStaffMode()) {
                $item = $event->getItem();
                $block = $event->getBlock();
                if ($block->getId() === Block::CHEST) {
                    $event->setCancelled();
                    return;
                }
                switch ($item->getId()) {
                    case Item::CONCRETE:
                        if ($item->getDamage() === 5) {
                            $player->setChatMode(CorePlayer::PUBLIC);
                            $player->getInventory()->setItem(1, Item::get(Item::CONCRETE, 14, 1)->setCustomName(TextFormat::ITALIC . TextFormat::RED . "Staff Chat"));
                            $player->sendMessage(Translation::getMessage("chatModeSwitch", [
                                "mode" => TextFormat::GREEN . strtoupper($player->getChatModeToString())
                            ]));
                        } elseif ($item->getDamage() === 14) {
                            $player->setChatMode(CorePlayer::STAFF);
                            $player->getInventory()->setItem(1, Item::get(Item::CONCRETE, 5, 1)->setCustomName(TextFormat::ITALIC . TextFormat::GREEN . "Staff Chat"));
                            $player->sendMessage(Translation::getMessage("chatModeSwitch", [
                                "mode" => TextFormat::GREEN . strtoupper($player->getChatModeToString())
                            ]));
                        }
                        break;
                    case Item::ICE:
                        $player->sendMessage("§l§8»§r §7You must tap a player with this item to freeze/unfreeze them!");
                        break;
                    case Item::MOB_HEAD:
                        $event->setCancelled();
                        $randomPlayer = $this->core->getServer()->getOnlinePlayers()[array_rand($this->core->getServer()->getOnlinePlayers())];
                        if ($randomPlayer instanceof CorePlayer) {
                            $player->teleport($randomPlayer->asPosition());
                            $player->sendMessage("§l§8»§r §7You have teleported to " . TextFormat::GREEN . $randomPlayer->getName() . "§r§7.");
                        }
                        break;
                    case Item::BOOK:
                        $player->sendMessage("§l§8»§r §7You must tap a player with this item to see their inventory!");
                        break;
                }
            }
        }
    }

    public function onEntityDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof CorePlayer) {
            if ($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                if ($damager instanceof CorePlayer) {

					$event->setAttackCooldown(9);

					$event->setKnockback($event->getKnockback() - 0.01999999);

					if($damager->getFloatingText("Damage") !== null)
					{

						$damager->removeFloatingText("Damage");

					}

					$damager->addFloatingText(new Position($entity->getX(), $entity->getY() + 1, $entity->getZ(), $entity->getLevel()), "Damage", "§4-§c{$event->getFinalDamage()}");

                    if ($damager->isInStaffMode()) {
                        $event->setCancelled();
                        switch ($damager->getInventory()->getItemInHand()->getId()) {
                            case Item::ICE:
                                $entity->setImmobile(!$entity->isImmobile());
                                $damager->sendMessage($entity->isImmobile() ? "§7You have successfully §l§aENABLED§r §7freeze on " . TextFormat::GOLD . $entity->getName() . "§7!" : "§l§8(§a!§8)§r §7You have successfully §l§cDISABLED§r §7freeze on " . TextFormat::GOLD . $entity->getName() . "§7!");
                                break;
                            case Item::BOOK:
//                               // TODO read player inventory
//                                $damager->addWindow(SyncInventory::load($entity->getName())); // gotta fix coming soon
                                break;
                        }
                    }
                }
            }
        }
    }

    public function onCommandPreProcess(PlayerCommandPreprocessEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
            if (substr($event->getMessage(), 0, 1) === "/") {
                $command = substr(explode(" ", $event->getMessage())[0], 1);
                if (strtolower($command) === "tp" or strtolower($command) === "teleport") {
                    if ($player->isInStaffMode()) {
                        $player->sendMessage("§7You can not use this while in staff mode!");
                        $event->setCancelled();
                        return;
                    }
                }
            }
        }
    }

    public function onStaffModeQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
            if ($player->isInStaffMode()) {
                $player->setStaffMode(false);
            }
        }
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onPlayerExhaust(PlayerExhaustEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player instanceof CorePlayer) {
           if($player->hasFeedCooldown()) {
               $event->setCancelled();
           }
        }
    }
	private $xp;
	public function onDeath(PlayerDeathEvent $event){
		$this->xp[$event->getPlayer()->getName()] = $event->getPlayer()->getXpLevel();
	}
	public function onRespawn(PlayerRespawnEvent $event){
		$event->getPlayer()->setXpLevel(0);
	}
}
