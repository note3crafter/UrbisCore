<?php

declare(strict_types = 1);

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\player\PlayerMoveEvent;

class OverloadEnchantment extends Enchantment {

    /**
     * OverloadEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::OVERLOAD, "Overload", self::RARITY_COMMON, "Obtain more health than you have.", self::MOVE, self::SLOT_ARMOR, 3);
        $this->callable = function(PlayerMoveEvent $event, int $level) {
            $player = $event->getPlayer();
            if((!$player->hasEffect(Effect::HEALTH_BOOST)) or $player->getEffect(Effect::HEALTH_BOOST)->getDuration() <= 20) {
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::HEALTH_BOOST), 30, $level));
            }
            return;
        };
    }
}
