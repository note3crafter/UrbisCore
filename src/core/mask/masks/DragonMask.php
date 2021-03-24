<?php

declare(strict_types = 1);

namespace core\mask\masks;

use core\CorePlayer;
use core\utils\Utils;
use pocketmine\entity\Effect;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use jacknoordhuis\combatlogger\CombatLogger;

class DragonMask extends MaskAPI{

    /**
     * @return string
     */
    public function getName(): string{
        return "Dragon Mask";
    }

    /**
     * @return int
     */
    public function getDamage(): int{
        return 5;
    }

    /**
     * @return array
     */
    public function getLore(): array{
        return [
            "\n§r§l§bRARITY§r§7: §bLEGENDARY",
            " ",
            "§r§l§6ABILITIES:",
            "§r§r§l§8*§r §9Have an ablity to fly and gain powerful effects.",
            " ",
            "§r§l§6EFFECTS:",
            "§r§l§8*§r §9Speed 4",
            "§r§l§8*§r §9Regeneration",
            "§r§l§8*§r §9RNight Vision",
            "§r§l§8*§r §9Fire Resistance",
            "§r§l§8*§r §9Resistance",
            "§r§l§8*§r §9Strength I",
        ];
    }

    /**
     * @param int $currentTick
     */
    public function tick(int $currentTick): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($this->hasMask($p)){
                $p->setAllowFlight(true);
                if($p instanceof CorePlayer){
                    if($p->getAllowFlight() == true and $p->isTagged()){
                        $p->setAllowFlight(false);
                        $p->setFlying(false);
                    }else{
                        $p->setAllowFlight(true);
                    }
                }
                Utils::addEffect($p, Effect::REGENERATION, 6, 0);
				Utils::addEffect($p, Effect::SPEED, 6, 4);
				Utils::addEffect($p, Effect::NIGHT_VISION, 30);
				Utils::addEffect($p, Effect::FIRE_RESISTANCE, 6);
				Utils::addEffect($p, Effect::STRENGTH, 6, 0);
				Utils::addEffect($p, Effect::RESISTANCE, 6, 0);
            }
        }
    }
}
