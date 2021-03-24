<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SkeletonMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Skeleton Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 0;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §aCOMMON",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Look in their eye and shoot at them",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed III",
            "§r§l§8*§r §9Haste II",
            "§r§l§8*§r §9Jump Boost V",
            "§r§l§8*§r §9Night Vision",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::SPEED, 6, 3);
                Utils::addEffect($p, Effect::HASTE, 6, 2);
                Utils::addEffect($p, Effect::JUMP, 6);
                Utils::addEffect($p, Effect::NIGHT_VISION, 15);
            }
        }
    }
}