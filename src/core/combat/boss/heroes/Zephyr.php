<?php

namespace core\combat\boss\heroes;

use core\Urbis;
use core\CorePlayer;
use core\combat\boss\Boss;
use core\item\ItemManager;
use core\entity\EntityAI;
use core\item\types\BossChest;
use core\item\types\Artifact;
use core\item\types\GreekCrate;
use core\item\types\HolyBox;
use core\item\types\MoneyNote;
use core\item\types\XPNote;
use core\item\types\ChestKit;
use core\item\types\RankShard;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class Zephyr extends Boss
{
    const BOSS_ID = 4;

    /**
     * Alien constructor.
     *
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt) {
        $path = Urbis::getInstance()->getDataFolder() . "skins" . DIRECTORY_SEPARATOR . "zephyr.png";
        $this->setSkin(Utils::createSkin(Utils::getSkinDataFromPNG($path)));
        parent::__construct($level, $nbt);
        $this->setMaxHealth(1000);
        $this->setHealth(1000);
        $this->setNametag(TextFormat::BOLD . TextFormat::GREEN . "Zephyr " . TextFormat::RESET . TextFormat::RED . $this->getHealth() . TextFormat::RESET . "/" . TextFormat::RED . $this->getMaxHealth() . TextFormat::RESET);
        $this->setScale(0.5);
        $this->attackDamage = 50;
        $this->speed = 5;
        $this->attackWait = 0.5;
        $this->regenerationRate = 30;
    }

    /**
     * @param int $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool
    {
        parent::entityBaseTick($tickDiff);
        $this->setNametag(TextFormat::GOLD . "Zephyr" . TextFormat::EOL . TextFormat::WHITE . $this->getHealth() . TextFormat::RED . "♡");
        $this->sendData($this->getViewers());
        return parent::entityBaseTick($tickDiff);
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        $drops = [];
        for ($i = 0; $i < 2; $i++) {
            $drops[] = (new XPNote(5000))->getItemForm();
            $drops[] = (new MoneyNote(50000))->getItemForm();
            $drops[] = (new Artifact(1))->getItemForm();
            $drops[] = (new BossChest(1))->getItemForm();
            $drops[] = (new RankShard(1))->getItemForm();
        }
        if (mt_rand(0, 100) <= 8) {
            $drops[] = (new HolyBox($kit))->getItemForm();
            $drops[] = (new GreekCrate())->getItemForm();
        }
        if (mt_rand(0, 100) <= 35) {
            $kits = Urbis::getInstance()->getKitManager()->getKits();
            $kit = $kits[array_rand($kits)];
            $drops[] = (new ChestKit($kit))->getItemForm();
        }
        return $drops;
    }


    protected function onDeath(): void
    {
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
        $drops = $this->getDrops();
        if ($p instanceof CorePlayer) {
            Server::getInstance()->broadcastMessage($p->getDisplayName() . TextFormat::GRAY . " has dealt the most damage " . TextFormat::DARK_GRAY . "(" . TextFormat::WHITE . $this->getLastDamageCause()->getFinalDamage() . TextFormat::RED . TextFormat::BOLD . " DMG" . TextFormat::RESET . TextFormat::DARK_GRAY . ")" . TextFormat::GRAY . " to " . TextFormat::BOLD . TextFormat::GREEN . $this->getNameTag() . TextFormat::RESET . TextFormat::GRAY . "and received:");
            foreach ($drops as $item) {
                $name = TextFormat::RESET . TextFormat::WHITE . $item->getName();
                if ($item->hasCustomName()) {
                    $name = $item->getCustomName();
                }
                Server::getInstance()->broadcastMessage($name . TextFormat::RESET . TextFormat::GRAY . " * " . TextFormat::WHITE . $item->getCount());
                $p->getLevel()->dropItem($p, $item);
            }
        }
    }
}
