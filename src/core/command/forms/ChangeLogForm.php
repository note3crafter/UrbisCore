<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\libs\form\CustomForm;
use core\libs\form\element\Label;
use pocketmine\utils\TextFormat;

class ChangeLogForm extends CustomForm {

    /**
     * ChangeLogForm constructor.
     */
    public function __construct() {
        $title = "§l§cChangelog";
        $elements = [];
        $elements[] = new Label("Changes",  "§6Urbis has now developed and brought to season 1 along\n§6with better and new features.\n\n§r - Added Shards\n§r - Added Mob Coins\n - New KoTH System \n - Added Specials Shop\n - View Crate Rewards\n - Fixed Custom Enchants\n \n§bDiscord: §7https://discord.gg/s8ZAH2Ur9d §f \n§bVote at: §7gg.vote/urbis§7 §f\n§bStore: §7https://urbispestore.tebex.io§f");
        parent::__construct($title, $elements);
    }
}