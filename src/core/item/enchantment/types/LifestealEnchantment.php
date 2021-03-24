<?php

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use core\translation\Translation;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class LifestealEnchantment extends Enchantment {

    /**
     * LifestealEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::LIFESTEAL, "Lifesteal", self::RARITY_MYTHIC, "Have a chance to regain lots of health but chance to get weakness at the same time", self::DAMAGE, self::SLOT_SWORD, 10);
        $this->callable = function(EntityDamageByEntityEvent $event, int $level) {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if((!$entity instanceof Player) or (!$damager instanceof Player)) {
                return;
            }
            $random = mt_rand(1, 200);
            $chance = $level * 3;
            if($chance >= $random) {
				$randomHeal = mt_rand(1, 12);
				$randomDamage = mt_rand(1, 4);
				$entity->setHealth($entity->getHealth() - $randomDamage);
                $damager->setHealth($damager->getHealth() + $randomHeal);
                $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::WEAKNESS)), 5 * 20, 1);
                $entity->sendMessage("§c»»§r §7Opponent Lifesteal");
                $damager->sendMessage("§6»»§r §7Lifesteal enabled!");
            }
        };
    }
}