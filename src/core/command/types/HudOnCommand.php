<?php


namespace core\command\types;


use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class HudOnCommand extends Command
{

    /**
     * HudOnCommand constructor.
     */
    public function __construct()
    {
        parent::__construct("hudon", "Toggle Hud", "/hudon");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }

        $sender->getScoreboard(true);
    }
}
