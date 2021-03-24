<?php

namespace core\combat\boss\minions;

use core\combat\boss\Boss;
use core\combat\boss\Minion;
use core\item\ItemManager;
use core\item\types\Artifact;
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
use pocketmine\item\GoldenAppleEnchanted;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ThamuzMinion extends Minion {

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
        $this->setMaxHealth(300);
        $this->setHealth(300);
        $this->bossName = "Thamuz Minion";
        $this->setNametag("§l§6Thamuz§r §7Minion " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::RESET . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        $this->setScale(0.5);
        $this->attackDamage = 2;
        $this->speed = 1.5;
        $this->attackWait = 3;
        $this->regenerationRate = 0;
    }

    /**
     * @param int $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool {
        $this->setNametag("§l§6Thamuz§r §7Minion" . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::WHITE . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        return parent::entityBaseTick($tickDiff);
    }

    public function onDeath(): void {
        $rewards = [
            (new Artifact())->getItemForm()->setCount(mt_rand(1, 4)),
            (new XPNote(mt_rand(250, 500)))->getItemForm(),
        ];

        foreach($rewards as $item) {
            $this->getLevel()->dropItem($this, $item);
        }

        if($this->owner->isAlive()){
            $this->owner->setHealth($this->owner->getHealth() - 50);
            foreach ($this->level->getPlayers() as $p){
                if($p->distance($this) <= 200){
                    $p->sendMessage("");
                }
            }
        }
    }
}
