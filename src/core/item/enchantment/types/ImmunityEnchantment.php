<?php

declare(strict_types = 1);

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use core\CorePlayer;
use core\translation\Translation;
use pocketmine\event\entity\EntityEffectAddEvent;

class ImmunityEnchantment extends Enchantment {

    /**
     * ImmunityEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::IMMUNITY, "Immunity", self::RARITY_MYTHIC, "Have a chance to remove negative effects.", self::EFFECT_ADD, self::SLOT_ARMOR, 3);
        $this->callable = function(EntityEffectAddEvent $event, int $level) {
            $effect = $event->getEffect();
            if(!$effect->getType()->isBad()) {
                return;
            }
            $entity = $event->getEntity();
            if(!$entity instanceof CorePlayer) {
                return;
            }
            $random = mt_rand(1, 12);
            if($level >= $random) {
                $event->setCancelled();
                $entity->sendMessage("§6»»§r §7Immunity");
                return;
            }
        };
    }
}