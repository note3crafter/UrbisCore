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

class ShopForm extends MenuForm {

    /**
     * ShopForm constructor.
     */
    public function __construct() {
        $title = "§l§cShop";
        $text = "Select any of this options to purchase.";
        $options = [];
        /** @var ShopPlace $place */
        foreach(Urbis::getInstance()->getPriceManager()->getPlaces() as $place) {
            $options[] = new MenuOption($place->getName());
        }
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        $option = $this->getOption($selectedOption);
        $text = $option->getText();
        $player->sendForm(new ItemListForm(Urbis::getInstance()->getPriceManager()->getPlace($text)));
    }
}