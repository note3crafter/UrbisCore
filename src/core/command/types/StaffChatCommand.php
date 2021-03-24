<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class StaffChatCommand extends Command {

    /**
     * StaffChatCommand constructor.
     */
    public function __construct() {
        parent::__construct("staffchat", "Toggle staff chat.", "/staffchat", ["sc"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if((!$sender instanceof CorePlayer) or (!$sender->hasPermission("permission.staff"))) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        $mode = CorePlayer::PUBLIC;
        if($sender->getChatMode() !== CorePlayer::STAFF) {
            $mode = CorePlayer::STAFF;
        }
        $sender->setChatMode($mode);
        $sender->sendMessage(Translation::getMessage("chatModeSwitch", [
            "mode" =>  TextFormat::GREEN . strtoupper($sender->getChatModeToString())
        ]));
    }
}