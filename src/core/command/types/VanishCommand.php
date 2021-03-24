<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;

class VanishCommand extends Command {

    /**
     * VanishCommand constructor.
     */
    public function __construct() {
        parent::__construct("vanish", "Toggle vanish mode", "/vanish <on/off>");
        $this->setAliases(["v"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if((!$sender instanceof CorePlayer) or ((!$sender->isOp()) and (!$sender->hasPermission("permission.staff")))) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        if(!isset($args[0])) {
            $sender->sendMessage(Translation::getMessage("usageMessage", [
                "usage" => $this->getUsage()
            ]));
            return;
        }
        switch($args[0]) {
            case "on":
                $sender->vanish();
                $sender->sendMessage(Translation::getMessage("vanishToggle"));
                break;
            case "off":
                $sender->vanish(false);
                $sender->sendMessage(Translation::getMessage("vanishToggle"));
                break;
            default:
                $sender->sendMessage(Translation::getMessage("usageMessage", [
                    "usage" => $this->getUsage()
                ]));
        }
    }
}