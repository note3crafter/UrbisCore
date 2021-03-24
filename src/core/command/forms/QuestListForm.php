<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\Urbis;
use core\CorePlayer;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class QuestListForm extends MenuForm {

    /**
     * QuestListForm constructor.
     */
    public function __construct() {
        $title = "§l§eActive Quests";
        $text = "Which quest would you like to start?";
        $options = [];
        foreach(Urbis::getInstance()->getQuestManager()->getActiveQuests() as $quest) {
            $options[] = new MenuOption($quest->getName());
        }
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
        $player->sendForm(new QuestInfoForm($player, $option->getText()));
    }

    /**
     * @param Player $player
     */
    public function onClose(Player $player): void {
        $player->sendForm(new QuestMainForm());
    }
}