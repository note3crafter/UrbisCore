<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\CorePlayer;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;

class QuestMainForm extends MenuForm {

    /**
     * QuestMainForm constructor.
     */
    public function __construct() {
        $title = "§l§dQuest Menu§r";
		$text = "What quest would you like to undertake?";
        $options = [];
        $options[] = new MenuOption("Active Quests");
        $options[] = new MenuOption("Quest Shop");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        if(!$player instanceof CorePlayer) {
            return;
        }
        $option = $this->getOption($selectedOption);
        switch($option->getText()) {
            case "Active Quests":
                $player->sendForm(new QuestListForm());
                break;
            case "Quest Shop":
                $player->sendForm(new QuestShopForm($player));
                break;
        }
    }
}