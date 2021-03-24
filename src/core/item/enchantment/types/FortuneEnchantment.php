<?php

declare(strict_types = 1);

namespace core\item\enchantment\types;

use core\item\enchantment\Enchantment;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;

class FortuneEnchantment extends Enchantment {

    /**
     * HasteEnchantment constructor.
     */
    public function __construct() {
        parent::__construct(self::FORTUNE, "Fortune", self::RARITY_UNCOMMON, "Chance to multiply drops from ores", self::BREAK, self::SLOT_DIG, 3);
        $this->callable = function(BlockBreakEvent $event, int $level) {
            $player = $event->getPlayer();
            $block = $event->getBlock();
			if(in_array($block->getId(), [Item::COAL_ORE, Item::EMERALD_ORE, Item::LAPIS_ORE, Item::DIAMOND_ORE])){
				$drops = $event->getDrops();
				foreach ($drops as $drop){
				    $drop->setCount($drop->getCount() & $level);
                }
				$event->setDrops($drops);
			}
            return;
        };
    }
}