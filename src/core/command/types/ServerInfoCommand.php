<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\forms\ServerInfoForm;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ServerInfoCommand extends Command {

    /** @var ServerInfoForm */
    private $form;

    /**
     * ServerInfoCommand constructor.
     */
    public function __construct() {
        parent::__construct("serverinfo", "know about the server and guide.");
        $this->form = new ServerInfoForm();
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
        $sender->sendForm($this->form);
    }
}