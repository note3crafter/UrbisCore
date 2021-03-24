<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CreeperMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Creeper Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 4;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §cRARE",
            " ",
            "§r§l§6ABILITIES:",
            "§r§r§l§8*§r §9Chance to blew your enemy up while looking at them!",
            " ",
            "§r§l§6EFFECTS:",
            "§r§l§8*§r §9Speed V",
            "§r§l§8*§r §9Regeneration",
            "§r§l§8*§r §9RNight Vision",
            "§r§l§8*§r §9Haste II",
            "§r§l§8*§r §9Invisibility",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::NIGHT_VISION, 15);
                Utils::addEffect($p, Effect::REGENERATION, 6);
				Utils::addEffect($p, Effect::INVISIBILITY, 6);
				Utils::addEffect($p, Effect::SPEED, 5);
				Utils::addEffect($p, Effect::HASTE, 5);
            }
        }
    }
}
