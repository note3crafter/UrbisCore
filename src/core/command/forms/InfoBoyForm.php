<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\command\forms\ChangelogForm;
use core\command\forms\RulesForm;
use core\Urbis;
use core\CorePlayer;
use core\price\ShopPlace;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class InfoBoyForm extends MenuForm {

    /**
     * InfoBoyForm constructor.
     */
    public function __construct() {
        $title = "§l§cInformation Boy";
        $text = "Options-";
        $options = [];
        $options[] = new MenuOption("View Changelogs");
        $options[] = new MenuOption("View Server Rules");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        $option = $this->getOption($selectedOption);
        $text = $option->getText();
        if($text === "View Changelogs" and $player instanceof CorePlayer) {
            $player->sendForm(new ChangelogForm($player));
            return;
        }
        if($text === "View Server Rules" and $player instanceof CorePlayer) {
            $player->sendForm(new RulesForm($player));
            return;
        }
    }
}