<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class BroadcastCommand extends Command {

    /**
     * BroadcastCommand constructor.
     */
    public function __construct() {
        parent::__construct("broadcast", "Broadcast messages.", null, ["bc", "announce"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($sender->isOp() or $sender instanceof ConsoleCommandSender) {
            $message = implode(" ", $args);
            $message = str_replace("&", TextFormat::ESCAPE, $message);
			$this->getCore()->getServer()->broadcastMessage($message);
			$this->getCore()->getServer()->broadcastTitle($message);
            return;
        }
        $sender->sendMessage(Translation::getMessage("noPermission"));
        $this->setCancelled();
    }
}