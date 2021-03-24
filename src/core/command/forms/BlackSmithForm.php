<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\Urbis;
use core\CorePlayer;
use core\price\ShopPlace;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BlackSmithForm extends MenuForm {

    /**
     * BlackSmithForm constructor.
     */
    public function __construct() {
        $title = "§l§6Blacksmith";
        $text = "Select any of this options to purchase.";
        $options = [];
        $options[] = new MenuOption("Repair");
        $options[] = new MenuOption("Rename Item");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        $option = $this->getOption($selectedOption);
        $text = $option->getText();
        if($text === "Repair" and $player instanceof CorePlayer) {
            $player->sendForm(new RepairForm($player));
            return;
        }
        if($text === "Rename Item" and $player instanceof CorePlayer) {
            $player->sendForm(new RenameItemForm($player));
            return;
        }
    }
}