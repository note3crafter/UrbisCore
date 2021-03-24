<?php



namespace core\item\enchantment\types;



use core\item\enchantment\Enchantment;

use core\CorePlayer;
use pocketmine\Player;

use core\translation\Translation;

use pocketmine\event\entity\EntityDamageByEntityEvent;



class BleedEnchantment extends Enchantment {



    /**

     * BleedEnchantment constructor.

     */

    public function __construct() {

        parent::__construct(self::BLEED, "Bleeding", self::RARITY_MYTHIC, "Has a chance to multiply your damage 3x which is enought to bring someone from 20 hp to 5 but really low chance and have a higher chance to do so depending on the level of the enchant.", self::DAMAGE, self::SLOT_SWORD, 5);

        $this->callable = function(EntityDamageByEntityEvent $event, int $level) {

            $entity = $event->getEntity();

            $damager = $event->getDamager();

            if((!$entity instanceof CorePlayer) or (!$damager instanceof CorePlayer)) {
                return;
            }
            $random = mt_rand(1, 150);
            $chance = $level * 3;
            if($chance >= $random) {
				$enchant = "null";
				if($level == 1){

					$enchant = "§eBleeding§r";
				}

				if($level == 2){

					$enchant = "§9Bleeding§r";

				}

				if($level == 3){

					$enchant = "§6Bleeding§r";

				}

				if($level == 4){

					$enchant = "§cBleeding§r";

				}

				if($level == 5){

					$enchant = "§4Bleeding§r";

				}

                $event->setBaseDamage($event->getBaseDamage() * 2);

                $damager->sendMessage("\n§6»»§r" . $enchant . " §r§7has Activated!" . "\n");

            }

        };

    }

}