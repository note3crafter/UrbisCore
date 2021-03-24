<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\Urbis;
use core\libs\form\CustomForm;
use core\libs\form\element\Label;
use pocketmine\utils\TextFormat;

class RulesForm extends CustomForm {

    /**
     * CEInfoForm constructor.
     */
    public function __construct() {
        $title = "§l§aRules";
        $elements[] = new Label("rules", file_get_contents(Urbis::getInstance()->getDataFolder() . "rules.txt"));
        parent::__construct($title, $elements);
    }
}