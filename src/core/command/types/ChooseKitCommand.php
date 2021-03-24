<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\forms\ChooseKitForm;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ChooseKitCommand extends Command {

    /** @var ChooseKitForm */
    private $form;

    /**
     * WarpCommand constructor.
     */
    public function __construct() {
        parent::__construct("choosekit", "choose a kit testing ");
        $this->form = new ChooseKitForm();
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
        $sender->sendForm(new ChooseKitForm($sender));
    }
}