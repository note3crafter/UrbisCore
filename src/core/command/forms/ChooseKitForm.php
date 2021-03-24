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

class ChooseKitForm extends MenuForm {

    /**
     * ChooseKitForm constructor.
     */
    public function __construct() {
        $title = "§l§cKits";
        $text = "Select any of this options to choose kits.";
        $options = [];
        $options[] = new MenuOption("Normal Kits");
        $options[] = new MenuOption("Godly Kits");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        $option = $this->getOption($selectedOption);
        $text = $option->getText();
        if($text === "Normal Kits" and $player instanceof CorePlayer) {
            $kits = $this->getCore()->getKitManager()->getKits();
            $player->sendForm(new KitListForm($kits));
            return;
        }
        if($text === "Godly Kits" and $player instanceof CorePlayer) {
            $gkits = $this->getCore()->getKitManager()->getGodlyKits();
            $player->sendForm(new KitListForm($gkits));
            return;
        }
    }
}