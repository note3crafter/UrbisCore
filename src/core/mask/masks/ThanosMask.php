<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ThanosMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Thanos Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 9;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §bLEGENDARY",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Be that strong immune to Lifesteal and other Enchants.",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Regeneration 2",
            "§r§l§8*§r §9Health Boost",
            "§r§l§8*§r §9Speed 3",
            "§r§l§8*§r §9Jump Boost",
            "§r§l§8*§r §9Strength 2",
            "§r§l§8*§r §9Night Vision",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::SATURATION, 6);
                Utils::addEffect($p, Effect::HEALTH_BOOST, 6, 1);
                Utils::addEffect($p, Effect::REGENERATION, 6, 2);
                Utils::addEffect($p, Effect::STRENGTH, 6, 2);
                Utils::addEffect($p, Effect::SPEED, 6, 2);
                Utils::addEffect($p, Effect::NIGHT_VISION, 250, 2);
            }
        }
    }
}