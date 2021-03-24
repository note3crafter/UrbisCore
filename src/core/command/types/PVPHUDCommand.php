<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\utils\UtilsException;
use pocketmine\command\CommandSender;

class PVPHUDCommand extends Command {

    /**
     * VanishCommand constructor.
     */
    public function __construct() {
        parent::__construct("pvphud", "Toggle pvp hud", "/pvphud");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @throws TranslationException
     * @throws UtilsException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        $sender->togglePVPHUD();
        $sender->sendMessage("§l§a»§r §7Successfully turned on your PvP HUD.");
    }
}
