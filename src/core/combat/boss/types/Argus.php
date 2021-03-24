<?php

namespace core\combat\boss\types;

use core\combat\boss\Boss;
use core\combat\boss\minions\ArgusMinion;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\LuckyBlock;
use core\item\types\MoneyNote;
use core\item\types\Artifact;
use core\item\types\XPNote;
use core\item\types\relics\LegendaryRelic;
use core\Urbis;
use core\CorePlayer;
use core\item\types\boxes\BossChest;
use core\utils\Utils;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Argus extends Boss {

    const BOSS_ID = 3;

    /**
     * Argus constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt) {
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins/" . Urbis::getInstance()->bossData->get("skin");
        $this->setSkin(Utils::createSkin(Utils::getSkinDataFromPNG($path)));
        parent::__construct($level, $nbt);
        $this->setMaxHealth(Urbis::getInstance()->bossData->get("stats")["health"]);
        $this->setHealth(Urbis::getInstance()->bossData->get("stats")["health"]);
		$this->bossName = "ARGUS Boss";
        $this->setNametag(TextFormat::BOLD . TextFormat::GREEN. "Argus " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::GRAY . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        $this->setScale(Urbis::getInstance()->bossData->get("stats")["scale"]);
        $this->attackDamage = Urbis::getInstance()->bossData->get("stats")["attackDamage"];
        $this->speed = Urbis::getInstance()->bossData->get("stats")["speed"];
        $this->attackWait = Urbis::getInstance()->bossData->get("stats")["attackWait"];
        $this->regenerationRate = Urbis::getInstance()->bossData->get("stats")["regenerationRate"];
        $this->minion = 0;
    }

    /**
     * @param int $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool {
        $this->setNametag(TextFormat::BOLD . TextFormat::GREEN . "Argus " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::GRAY . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        if(is_int($this->minion) && $this->getHealth() <= $this->getMaxHealth() / 3){
            $this->minion = new ArgusMinion($this->level, Entity::createBaseNBT($this));
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
                    $p->sendMessage("§l§4» The ARGUS Boss has sent in minions to assist");
                }
            }
        }
        return parent::entityBaseTick($tickDiff);
    }

    public function onDeath(): void {
        $rewards = [
            (new Artifact())->getItemForm()->setCount(mt_rand(1, 2)),
            (new BossChest())->getItemForm()->setCount(mt_rand(1, 2)),
            (new MoneyNote(mt_rand(10000, 50000)))->getItemForm(),
            (new XPNote(mt_rand(10000, 50000)))->getItemForm(),
        ];
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
        //top
        $keys = ["Legendary" => 1, "Epic" => 2, "Rare" => 5];
        $rand = array_rand($keys);
        $crate = Urbis::getInstance()->getCrateManager()->getCrate($rand);
        if($p instanceof CorePlayer){
            $p->addKeys($crate, $keys[$rand]);
        }

        Server::getInstance()->broadcastMessage($p->getDisplayName() . TextFormat::WHITE . " has dealt the most damage " . TextFormat::DARK_GRAY . "(" . TextFormat::WHITE . $d . TextFormat::RED . TextFormat::BOLD . " DMG" . TextFormat::RESET . TextFormat::DARK_GRAY . ")" . TextFormat::WHITE . " to " . TextFormat::BOLD . TextFormat::GREEN . "Argus " . TextFormat::RESET . TextFormat::WHITE . "and received:");
        foreach($rewards as $item) {
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


        //other
        foreach($this->damages as $player => $damage) {
            if($player === $p->getName()) {
                continue;
            }
            if(Server::getInstance()->getPlayer($player) === null) {
                continue;
            }
            $online = Server::getInstance()->getPlayer($player);
            $online->sendMessage(TextFormat::GRAY . "You dealt " . TextFormat::WHITE . $damage . TextFormat::RED . TextFormat::BOLD . " DMG" . TextFormat::GRAY . " to " . TextFormat::BOLD . TextFormat::GREEN . "Argus " . TextFormat::RESET . TextFormat::GRAY . "and received:");
            $rewards = [
                (new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm(),
                (new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm(),
                (new Artifact())->getItemForm()->setCount(mt_rand(1, 2)),
                (new LegendaryRelic())->getItemForm()->setCount(mt_rand(1, 1)),
                (new MoneyNote(mt_rand(100, 2500)))->getItemForm()
            ];
            if($p instanceof CorePlayer){
                $crate = Urbis::getInstance()->getCrateManager()->getCrate("Rare");
                $p->addKeys($crate, 2);
            }
            foreach($rewards as $item) {
                $name = TextFormat::RESET . TextFormat::WHITE . $item->getName();
                if($item->hasCustomName()) {
                    $name = $item->getCustomName();
                }
                $online->sendMessage($name . TextFormat::RESET . TextFormat::GRAY . " * " . TextFormat::WHITE . $item->getCount());
                if($online->getInventory()->canAddItem($item)) {
                    $online->getInventory()->addItem($item);
                    continue;
                }
                $online->getLevel()->dropItem($online, $item);
            }
        }
    }
}
