<?php

namespace core\combat\boss\types;

use core\combat\boss\Boss;
use core\combat\boss\minions\ThamuzMinion;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\EnchantmentRemover;
use core\item\types\HolyBox;
use core\item\types\boxes\BossChest;
use core\item\types\MoneyNote;
use core\item\types\SacredStone;
use core\item\types\SellWand;
use core\item\types\XPNote;
use core\Urbis;
use core\utils\Utils;
use pocketmine\entity\Entity;
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Thamuz extends Boss {

    const BOSS_ID = 1;

    /**
     * Thamuz constructor.
     *
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt) {
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . "Thamuz.png";
        $this->setSkin(Utils::createSkin(Utils::getSkinDataFromPNG($path)));
        parent::__construct($level, $nbt);
        $this->setMaxHealth(8500);
        $this->setHealth(8000);
        $this->bossName = "Thamuz";
        $this->setNametag("§l§6Thamuz " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::RESET . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        $this->setScale(1.5);
        $this->attackDamage = 10;
        $this->speed = 1;
        $this->attackWait = 1;
        $this->regenerationRate = 1;
        $this->minion = 0;
    }

    /**
     * @param int $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool {
        $this->setNametag("§l§6Thamuz " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::WHITE . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        if(is_int($this->minion) && $this->getHealth() <= $this->getMaxHealth() / 3){ // 1
            $this->minion = new ThamuzMinion($this->level, Entity::createBaseNBT($this));
            $this->minion->owner = $this;
        }
        if(!is_null($this->minion) && $this->getHealth() <= $this->getMaxHealth() / 3){ //spawns minion when boss is at 1/3 of health.. i think
            $this->minion->spawnToAll();
            $this->minion->x = $this->x;
            $this->minion->y = $this->y;
            $this->minion->z = $this->z;
            $this->minion = null;
            foreach ($this->level->getPlayers() as $p){
                if($p->distance($this) <= 100){
                    $p->sendMessage("§l§6Thamuz§r §7has sent in minions to assist");
                }
            }
        }
        return parent::entityBaseTick($tickDiff);
    }

    public function onDeath(): void {
        $kits = Urbis::getInstance()->getKitManager()->getGodlyKits();
        $kit = $kits[array_rand($kits)];
        $rewards = [
            (new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm(),
            (new HolyBox($kit))->getItemForm(),
            (new BossChest(1))->getItemForm(),
            (new MoneyNote(mt_rand(15000, 20000)))->getItemForm(),
            (new XPNote(mt_rand(250, 1000)))->getItemForm(),
            (new EnchantmentRemover(100))->getItemForm(),
            (new SellWand(100))->getItemForm()
        ];
        $drops = [];
        for($i = 0; $i <= 3; ++$i) {
            $drops[] = $rewards[array_rand($rewards)];
        }
        $d = null;
        $p = null;
        foreach($this->damages as $player => $damage) {
            if(Server::getInstance()->getPlayer($player) === null) {
                continue;
            }
            $online = Server::getInstance()->getPlayer($player);
            if($damage > $d) {
                $d = $damage;
                $p = $online;
            }
        }
        if($p === null) {
            return;
        }
        Server::getInstance()->broadcastMessage($p->getDisplayName() . " has dealt the most damage " . TextFormat::WHITE . $d . " DMG" . TextFormat::GRAY . " to Thamuz");
        foreach($drops as $item) {
            $name = TextFormat::RESET . TextFormat::WHITE . $item->getName();
            if($item->hasCustomName()) {
                $name = $item->getCustomName();
            }
            Server::getInstance()->broadcastMessage($name . TextFormat::RESET . TextFormat::GRAY . " * " . TextFormat::WHITE . $item->getCount());
            if($p->getInventory()->canAddItem($item)) {
                $p->getInventory()->addItem($item);
                continue;
            }
            $p->getLevel()->dropItem($p, $item);
        }
        foreach($this->damages as $player => $damage) {
            if($player === $p->getName()) {
                continue;
            }
            if(Server::getInstance()->getPlayer($player) === null) {
                continue;
            }
            $online = Server::getInstance()->getPlayer($player);
            $item = $rewards[array_rand($rewards)];
            $name = TextFormat::RESET . TextFormat::WHITE . $item->getName();
            if($item->hasCustomName()) {
                $name = $item->getCustomName();
            }
            $online->sendMessage(TextFormat::GRAY . "You dealt " . TextFormat::WHITE . $damage . TextFormat::RED . TextFormat::BOLD . " DMG" . TextFormat::GRAY . " to " . TextFormat::BOLD . TextFormat::AQUA . "META King " . TextFormat::RESET . TextFormat::GRAY . "and received:");
            $online->sendMessage($name . TextFormat::RESET . TextFormat::GRAY . " * " . TextFormat::WHITE . $item->getCount());
            if($online->getInventory()->canAddItem($item)) {
                $online->getInventory()->addItem($item);
                continue;
            }
            $online->getLevel()->dropItem($p, $item);
        }
    }
}
