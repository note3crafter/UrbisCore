<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\entity\forms\TinkerConfirmationForm;
use core\Urbis;
use core\CorePlayer;
use core\price\ShopPlace;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EnchanterForm extends MenuForm {

    /**
     * EnchanterForm constructor.
     */
    public function __construct() {
        $title = "§l§5Enchanter";
        $text = "Select any of this options to purchase.";
        $options = [];
        $options[] = new MenuOption("Purchase Enchantment Books");
        $options[] = new MenuOption("Trade");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        $option = $this->getOption($selectedOption);
        $text = $option->getText();
        if($text === "Purchase Enchantment Books" and $player instanceof CorePlayer) {
            $player->sendForm(new EnchantmentShopForm($player));
            return;
        }
        if($text === "Trade" and $player instanceof CorePlayer) {
            $player->sendForm(new TinkerConfirmationForm($player));
            return;
        }
    }
}