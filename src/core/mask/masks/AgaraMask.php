<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class AgaraMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Agara Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 6;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §aCOMMON",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9You don't feel fire and have speed",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed II",
            "§r§l§8*§r §9Fire Resistance",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::FIRE_RESISTANCE, 6, 4);
                Utils::addEffect($p, Effect::SPEED, 6, 2);
            }
        }
    }
}