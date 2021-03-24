<?php

namespace core\combat\boss\heroes;

use core\Urbis;
use core\CorePlayer;
use core\entity\EntityAI;
use core\item\types\Artifact;
use core\item\types\boxes\BossChest;
use core\item\types\HolyBox;
use core\item\types\MoneyNote;
use core\item\types\XPNote;
use core\item\types\ChestKit;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Phoenix extends EntityAI
{
    const NETWORK_ID = self::BLAZE;

    /** @var float */
    public $width = 0.6;

    /** @var float */
    public $height = 1.8;


    protected function initEntity(): void
    {
        parent::initEntity();
        $this->setMaxHealth(800);
        $this->setHealth(800);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTagVisible(true);
        $this->setNametag(TextFormat::AQUA. "AGARA " . TextFormat::RED . "❤" . $this->getHealth() . TextFormat::RED . "");
        $this->setScale(2);
        $this->attackDamage = 6;
        $this->speed = 0.3;
        $this->attackWait = 35;
        $this->regenerationRate = 0.5;
        $this->recalculateBoundingBox();

    }

    /**
     * @param int $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool
    {
        parent::entityBaseTick($tickDiff);
        $this->setNametag(TextFormat::AQUA. "AGARA " . TextFormat::RED . "❤" . $this->getHealth() . TextFormat::RED . "");
        $this->sendData($this->getViewers());
        return parent::entityBaseTick($tickDiff);
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        $drops = [];
        $drops[] = (new MoneyNote(50000))->getItemForm();
        $drops[] = (new XPNote(7500))->getItemForm();
        $drops[] = (new Artifact())->getItemForm();
        $drops[] = (new BossChest())->getItemForm();
        if (mt_rand(0, 100) <= 8) {
            $kits = Urbis::getInstance()->getKitManager()->getSacredKits();
            $kit = $kits[array_rand($kits)];
            $drops[] = (new HolyBox($kit))->getItemForm();
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

    public function getName(): string
    {
        return "Phoenix";
    }
}
