<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\rank\Rank;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class NickCommand extends Command {

    /**
     * NickCommand constructor.
     */
    public function __construct() {
        parent::__construct("nick", "Set your nickname to whatever you desire");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if((!$sender instanceof CorePlayer) or ($sender->getRank()->getIdentifier() < 8 and !in_array($sender->getRank()->getIdentifier(), [Rank::GLORIOUS, Rank::YOUTUBER]))){
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        $name = implode(" ", $args);
        if($name == "reset" or $name == "off"){
            $sender->setDisplayName($sender->getName());
            $sender->sendMessage("§l§a»§r §7You have successfully reset your nickname.");
            return;
		}
		$sender->setDisplayName("~" . $name . "~");
        $sender->setDisplayName("~" . $name . "~");
        $sender->sendMessage("§l§a»§r §7You nickname has been set to " . TextFormat::AQUA . $name);
        return;
    }
}