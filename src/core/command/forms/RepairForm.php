<?php

namespace core\command\forms;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\ModalForm;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class RepairForm extends ModalForm {

    /** @var int */
    private $cost;

    /**
     * RepairForm constructor.
     *
     * @param Player $player
     */
    public function __construct(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $levels = 0;
        foreach($item->getEnchantments() as $enchantment) {
            $levels = $levels + $enchantment->getLevel();
        }
        $damage = $item->getDamage();
        if($levels == 0) {
            $cost = $damage * 5;
        }
        else {
            $factor = $levels * 2;
            $cost = $item->getDamage() * $factor;
        }
		$this->cost = $cost;
		if($player->hasPermission("permission.tier2")){
			$title = "§l§cRepair";
			$text = "Would you like to repair the item you currently are holding? It will be free!";
		} else {
        	$title = "§l§cRepair";
			$text = "Would you like to repair the item you currently are holding? It will cost $$cost.";
		}
        parent::__construct($title, $text);
    }

    /**
     * @param Player $player
     * @param bool $choice
     *
     * @throws TranslationException
     */
    public function onSubmit(Player $player, bool $choice): void {
        if(!$player instanceof CorePlayer) {
            return;
        }
        if($choice == true) {
            if($player->getBalance() >= $this->cost && !$player->hasPermission("permission.tier2")) {
                $item = $player->getInventory()->getItemInHand();
                $player->getInventory()->setItemInHand($item->setDamage(0));
                $player->subtractFromBalance($this->cost);
                $player->sendMessage(Translation::getMessage("successRepair"));
                $player->getLevel()->addSound(new AnvilUseSound($player));
                return;
			}
			if($player->hasPermission("permission.tier2")){
				$item = $player->getInventory()->getItemInHand();
				$player->getInventory()->setItemInHand($item->setDamage(0));
                $player->sendMessage("§a§l(!) §r§aYou have successfully repaired this item for free!");
				$player->getLevel()->addSound(new AnvilUseSound($player));
				return;
			}
            $player->sendMessage(Translation::getMessage("notEnoughMoney"));
            return;
        }
        return;
    }
}
