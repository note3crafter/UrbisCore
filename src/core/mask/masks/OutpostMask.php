<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class OutpostMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Outpost Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 11;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §cRARE",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Capture mask x2 than normal and recieve good effects.",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed II",
            "§r§l§8*§r §9Health Boost",
            "§r§l§8*§r §9Regeneration",
            "§r§l§8*§r §9Strength I",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::SPEED, 6, 2);
                Utils::addEffect($p, Effect::HEALTH_BOOST, 5);
                Utils::addEffect($p, Effect::REGENERATION, 6);
                Utils::addEffect($p, Effect::STRENGTH, 6, 1);
            }
        }
    }
}