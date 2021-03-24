<?php

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use core\UrbisPlayer;
use core\translation\Translation;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class DoubleStrikeEnchantment extends Enchantment {

    /**
     * DoubleStrikeEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::DOUBLESTRIKE, "DoubleStrike", self::RARITY_MYTHIC, "Chance to attack twice in one swing.", self::DAMAGE, self::SLOT_SWORD, 3);
        $this->callable = function(EntityDamageByEntityEvent $event, int $level) {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if((!$entity instanceof UrbisPlayer) or (!$damager instanceof UrbisPlayer)) {
                return;
            }
            $random = mt_rand(1, 250);
            $chance = $level * 3;
            if($chance >= $random) {
				$randomStrike = mt_rand(3, 6);
				$damager->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), $level * 20, $randomStrike));
				$entity->setHealth($entity->getHealth() - 2);
				$entity->setHealth($entity->getHealth() - 1);
				$entity->setHealth($entity->getHealth() - 2);
                $entity->sendMessage("§6»»§r §7Opponent double striking!");
                $damager->sendMessage("§6»»§r §7You are double striking!*");
            }
        };
    }
}