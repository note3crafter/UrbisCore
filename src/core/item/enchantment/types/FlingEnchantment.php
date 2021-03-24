<?php

declare(strict_types = 1);

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use core\CorePlayer;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class FlingEnchantment extends Enchantment {

    /**
     * FlingEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::FLING, "Fling", self::RARITY_RARE, "Have a chance to send someone in the air.", self::DAMAGE, self::SLOT_SWORD, 5);
        $this->callable = function(EntityDamageByEntityEvent $event, int $level) {
            $entity = $event->getEntity();
            if(!$entity instanceof CorePlayer) {
                return;
            }
            $random = mt_rand(1, 200);
            $chance = $level * 2;
            if($chance >= $random) {
                $entity->setMotion($entity->getMotion()->add(0, 1 + (0.1 * $level), 0));
            }
        };
    }
}