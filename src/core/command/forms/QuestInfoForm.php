<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\Urbis;
use core\CorePlayer;
use core\libs\form\CustomForm;
use core\libs\form\element\Label;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class QuestInfoForm extends CustomForm {

    /**
     * QuestInfoForm constructor.
     *
     * @param CorePlayer $player
     * @param string        $quest
     */
    public function __construct(CorePlayer $player, string $quest) {
        $title = TextFormat::BOLD . TextFormat::WHITE . $quest;
        $quest = Urbis::getInstance()->getQuestManager()->getQuest($quest);
        $session = Urbis::getInstance()->getQuestManager()->getSession($player);
        $elements = [];
        $elements[] = new Label("Description", "Description: " . $quest->getDescription());
        $progress = $session->getQuestProgress($quest);
        $target = $quest->getTargetValue();
        if($progress === -1) {
            $progress = $target;
        }
        $elements[] = new Label("Progress", "Progress: $progress/$target");
        $elements[] = new Label("Difficulty", "Difficulty: " . $quest->getDifficultyName());
        $elements[] = new Label("Reward", "Reward: " . $quest->getDifficulty() . " Quest points");
        parent::__construct($title, $elements);
    }

    /**
     * @param Player $player
     */
    public function onClose(Player $player): void {
        $player->sendForm(new QuestListForm());
    }
}