<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MinerMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Miner Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 3;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §aCOMMON",
            " ",
            "§r§l§6ABILITIES:",
            "§r§l§8*§r §9With this mask you can mine faster with effects of haste.",
            " ",
            "§l§6EFFECTS:",
            "§r§l§8*§r §9Speed II",
            "§r§l§8*§r §9Night Vision II",
            "§r§l§8*§r §9Haste III",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                Utils::addEffect($p, Effect::HASTE, 6, 2);
                Utils::addEffect($p, Effect::SPEED, 6, 2);
                Utils::addEffect($p, Effect::NIGHT_VISION, 6, 2);
            }
        }
    }
}
