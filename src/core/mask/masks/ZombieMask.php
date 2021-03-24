<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ZombieMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Zombie Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 2;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §aCOMMON",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Worst Mask But if you like to be drowsy then use.",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed III",
            "§r§l§8*§r §9Haste II",
            "§r§l§8*§r §9Nausea",
            "§r§l§8*§r §9Night Vision",
            "§r§l§8*§r §9Slowness",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::SPEED, 6, 3);
                Utils::addEffect($p, Effect::HASTE, 6, 1);
                Utils::addEffect($p, Effect::NAUSEA, 6);
                Utils::addEffect($p, Effect::NIGHT_VISION, 15);
				Utils::addEffect($p, Effect::SLOWNESS, 6, 0);
            }
        }
    }
}