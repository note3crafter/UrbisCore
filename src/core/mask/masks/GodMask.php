<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\Server;
use pocketmine\entity\Effect;
use pocketmine\utils\TextFormat;

class GodMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "God Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 10;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §bLEGENDARY",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9Gain more health and be a god.",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed I",
            "§r§l§8*§r §9Regeneration I",
            "§r§l§8*§r §9Health Boost I",
            "§r§l§8*§r §9Fire Resistance",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::HEALTH_BOOST, 1);
                Utils::addEffect($p, Effect::REGENERATION, 6, 0);
                Utils::addEffect($p, Effect::SPEED, 6);
                Utils::addEffect($p, Effect::FIRE_RESISTANCE, 6);
            }
        }
    }
}