<?php

namespace core\combat\boss\minions;

use core\combat\boss\Boss;
use core\combat\boss\Minion;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\LuckyBlock;
use core\item\types\MoneyNote;
use core\item\types\Artifact;
use core\item\types\XPNote;
use core\item\types\LegendaryRelic;
use core\Urbis;
use core\CorePlayer;
use core\item\types\boxes\BossChest;
use core\utils\Utils;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ArgusMinion extends Minion {

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
        $this->setMaxHealth(200);
        $this->setHealth(200);
		$this->bossName = "ARGUS Minion";
        $this->setNametag("§l§aArgus§r §7Minion " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::GRAY . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        $this->setScale(0.5);
        $this->attackDamage = 1;
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
        $this->setNametag("§l§aArgus§r §7Minion " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::GRAY . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        return parent::entityBaseTick($tickDiff);
    }

    public function onDeath(): void {
        $rewards = [
            (new Artifact())->getItemForm()->setCount(mt_rand(1, 2)),
            (new XPNote(mt_rand(50, 100)))->getItemForm(),
        ];

        foreach($rewards as $item) {
            $this->getLevel()->dropItem($this, $item);
        }

        if($this->owner->isAlive()){
            $this->owner->setHealth($this->owner->getHealth() - 25);
            foreach ($this->level->getPlayers() as $p){
                if($p->distance($this) <= 200){
                    $p->sendMessage("");
                }
            }
        }
    }
}
