<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class WitherMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Wither Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 1;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §bLEGENDARY",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Amazing Abilites and Powerful Scary",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed III",
            "§r§l§8*§r §9Haste II",
            "§r§l§8*§r §9Strength",
            "§r§l§8*§r §9Night Vision",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
				Utils::addEffect($p, Effect::STRENGTH, 6);
                Utils::addEffect($p, Effect::NIGHT_VISION, 15);
                Utils::addEffect($p, Effect::SPEED, 6, 2);
                Utils::addEffect($p, Effect::HASTE, 6, 2);
            }
        }
    }
}