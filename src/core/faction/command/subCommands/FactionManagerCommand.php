<?php

declare(strict_types = 1);

namespace core\faction\command\subCommands;

use core\faction\Faction;
use core\command\utils\SubCommand;
use core\command\utils\Command;
use core\faction\forms\ManageForm;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class FactionManagerCommand extends SubCommand {

    /**
     * FactionManagerCommand constructor.
     */
    public function __construct() {
        parent::__construct("manage", "/faction manage");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        if($sender->getFaction() === null) {
            $sender->sendMessage(Translation::getMessage("beInFaction"));
            return;
		}
        else {
			$faction = $sender->getFaction();
            if($faction === null) {
                $sender->sendMessage(Translation::getMessage("invalidFaction"));
                return;
            }
        }
		$sender->sendForm(new ManageForm($sender));
    }
}