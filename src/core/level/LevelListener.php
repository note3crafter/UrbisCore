<?php

declare(strict_types = 1);

namespace core\level;

use core\item\types\Artifact;
use core\level\tile\Generator;
use core\level\tile\MobSpawner;
use core\item\types\notes\BloodyNote;
use core\item\types\LuckyBlock;
use core\faction\Faction;
use core\faction\FactionManager;
use core\faction\command\subCommands\TopSubCommand;
use core\Urbis;
use core\CorePlayer;
use core\utils\UtilsException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\sound\EndermanTeleportSound;
use core\item\types\relics\LegendaryRelic;
use core\item\types\relics\MythicRelic;
use core\item\types\relics\CommonRelic;
use core\item\types\relics\RareRelic;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\inventory\FurnaceSmeltEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\ProjectileHitBlockEvent;

class LevelListener implements Listener {

    /** @var Urbis */
    private $core;

    /**
     * LevelListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @throws UtilsException
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT name, strength FROM factions ORDER BY strength DESC LIMIT 10");
        $stmt->execute();
        $stmt->bind_result($name, $strength);
        $place = 1;
        $powerText = $powerText = "";
        while($stmt->fetch()) {
            $powerText .= "\n§e" . $place . "§6. §7" . $name . " §l§8|§r §c " . $strength . " §4STR";
            $place++;
        }
        $stmt->close();

        $stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT name, balance FROM factions ORDER BY balance DESC LIMIT 10");
        $stmt->execute();
        $stmt->bind_result($name, $balance);
        $place = 1;
        $valueText = $valueText = "";
        while($stmt->fetch()) {
            $valueText .= "\n§e" . $place ."§6. §7" . $name . " §l§8|§r §a" . $balance . "§2$ §cWORTH";
            $place++;
        }
        $stmt->close();

        $stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT username, balance FROM players ORDER BY balance DESC LIMIT 10");
        $stmt->execute();
        $stmt->bind_result($username, $pBalance);
        $place = 1;
        $balanceText = $balanceText = "";
        while($stmt->fetch()) {
            $balanceText .= "\n§e" . $place . "§6. §7" . $username . " §l§8|§r §a" . $pBalance . "§2$";
            $place++;
        }
        $stmt->close();

        $stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT username, shards FROM players ORDER BY shards DESC LIMIT 10");
        $stmt->execute();
        $stmt->bind_result($username, $pShards);
        $place = 1;
        $shardsText = $shardsText = "";
        while($stmt->fetch()) {
            $shardsText .= "\n§e" . $place . "§6. §7" . $username . " §l§8|§r §3" . $pShards . "";
            $place++;
        }

        $stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT username, mobcoins FROM players ORDER BY mobcoins DESC LIMIT 10");
        $stmt->execute();
        $stmt->bind_result($username, $pmobcoins);
        $place = 1;
        $mobcoinsText = $mobcoinsText = "";
        while($stmt->fetch()) {
            $mobcoinsText .= "\n§e" . $place . "§6. §7" . $username . " §l§8|§r §6" . $pmobcoins . "";
            $place++;
        }
        $stmt->close();
        $level = $this->core->getServer()->getDefaultLevel();
        $player->addFloatingText(new Position(92, 10, -1000, $level), "FTopPower", "§d§lTOP STRONGEST FACTIONS§r\n$powerText");
        $player->addFloatingText(new Position(97, 10, -996, $level), "FTopValue", "§d§lTOP RICHEST FACTIONS§r\n$valueText");
        $player->addFloatingText(new Position(97, 10, -991, $level), "BalanceTop", "§d§lTOP RICHEST BALANCE PLAYERS§r\n$balanceText");
        $player->addFloatingText(new Position(97, 10, -986, $level), "ShardsTop", "§d§lTOP RICHEST SHARDS PLAYERS§r\n$shardsText");
        //$player->addFloatingText(new Position(96, 10, -981, $level), "MobCoinsTop", "§c§lTOP RICHEST MOB COINS PLAYERS§r\n$mobcoinsText");
        //$player->addFloatingText(new Position(-87, 14, 120, $this->core->getServer()->getLevelByName("koth")), "Koth", "§b§lKOTH\n§l§8(§r§l§dAGARA§r§l§8)§r");
        $player->addFloatingText(new Position(67, 14, -1004, $this->core->getServer()->getLevelByName("Spawn")), "Info", "Welcome to §l§eUrbis§6MC§r Season I\n \n§7The server is now at season 1 to expect mo\n§7features than ever.\n \n§eUse your kit Starter\n§eto start your adventure!\n §6To check server updates do /changelog");
        $player->getLevel()->addSound(new EndermanTeleportSound($player));
    }
    
    /**
     * @priority HIGHEST
     * @param PlayerInteractEvent $event
     */
    public function onPlayerInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $block = $event->getBlock();
        $tile = $block->getLevel()->getTile($block);
        $item = $event->getItem();
        if($item->getNamedTag()->hasTag("EntityId")) {
            $entityId = $item->getNamedTag()->getInt("EntityId", -1);
            if($entityId < 10) {
                return;
            }
            if($tile instanceof MobSpawner and $tile->getEntityId() === $entityId and $tile->getStack() < 50) {
                $stack = $tile->getStack() + 1;
                $tile->setStack($stack);
                $player->sendPopUp("§aSTACKED: " . $stack . "/50");
                $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
        }
        if($tile instanceof Generator and $block->getItemId() === $item->getId() and $tile->getStack() < 25) {
            $stack = $tile->getStack() + 1;
            $tile->setStack($stack);
            $player->sendPopUp("§aSTACKED: " . $stack . "/25");
            $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
            $event->setCancelled();
        }
    }

    /**
     * @priority HIGHEST
     * @param BlockBreakEvent $event
     *
     * @throws TranslationException
     */
    public function onBlockBreak(BlockBreakEvent $event): void {
        if($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $block = $event->getBlock();
         if($block->getId() === Block::STONE && $player->getLevel()->getName() !== $player->getServer()->getDefaultLevel()->getName()) {
            if(mt_rand(150, 200) === mt_rand(130, 150)) {
                $item = new LegendaryRelic();
                if(!$player->getInventory()->canAddItem($item->getItemForm())){
                    return;
                }
                $player->getInventory()->addItem($item->getItemForm());
                $player->sendMessage("§7You found a §r§l§bLegendary §r§7Relic");
                $player->sendPopUp("§b+ 32 Shards");
                $player->addShards(32);
            }
            if(mt_rand(95, 180) === mt_rand(95, 180)) {
                $item = new MythicRelic();
                if(!$player->getInventory()->canAddItem($item->getItemForm())){
                    return;
                }
                $player->getInventory()->addItem($item->getItemForm());
                $player->sendMessage("§7You found a §r§l§dMythic §r§7Relic");
                $player->sendPopUp("§b+ 9 Shards");
                $player->addShards(18);
            }
            if(mt_rand(20, 200) === mt_rand(25, 250)) {
                $item = new RareRelic();
                if(!$player->getInventory()->canAddItem($item->getItemForm())){
                    return;
                }
                $player->getInventory()->addItem($item->getItemForm());
                $player->sendMessage("§7You found a §r§l§cRare §r§7Relic");
                $player->sendPopUp("§b+ 9 Shards");
                $player->addShards(9);
            }
            if(mt_rand(5, 100) === mt_rand(8, 100)) {
                $item = new CommonRelic();
                if(!$player->getInventory()->canAddItem($item->getItemForm())){
                    return;
                }
                $player->getInventory()->addItem($item->getItemForm());
                $player->sendMessage("§7You found a §r§l§aCommon §r§7Relic");
                $player->sendPopUp("§b+ 3 Shards");
                $player->addShards(3);
            }
            if(mt_rand(5, 800) === mt_rand(8, 800)) {
            $item = new BloodyNote();
            if(!$player->getInventory()->canAddItem($item->getItemForm())){
                $player->sendMessage("§l§b»§r §fYour inventory was full, you weren't able to get a §c§lBloody Note§r");
                return;
            }
            $player->getInventory()->addItem($item->getItemForm());
            $player->sendTitle("§l§cBloody Note§r", "§7has been found!");
            $player->sendPopUp("§b+ 22 Shards");
            $player->addShards(22);
            }
            if(mt_rand(0, 2500) <= 4) {
                $item = new Artifact();
                if(!$player->getInventory()->canAddItem($item->getItemForm())){
                    return;
                }
               $player->getInventory()->addItem($item->getItemForm());
                Server::getInstance()->broadcastMessage("§l§e" . $player->getName() . " §r§7has found an §l§bARTI§fFACT!§r");
            }
         }
    }

    /**
     * @priority LOWEST
     * @param FurnaceSmeltEvent $event
     */
    public function onFurnaceSmelt(FurnaceSmeltEvent $event): void {
		$id = $event->getResult()->getId();
		if ($id >= Block::PURPLE_GLAZED_TERRACOTTA and $id <= Block::BLACK_GLAZED_TERRACOTTA) {
			$event->setCancelled();
		}
	}
}