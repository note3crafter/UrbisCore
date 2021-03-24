<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\forms\CEInfoForm;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CEInfoCommand extends Command {

    /** @var CEInfoForm */
    private $form;

    /**
     * CEInfoCommand constructor.
     */
    public function __construct() {
        parent::__construct("ceinfo", "Acknowledge what each and every custom enchantment does.");
        $this->form = new CEInfoForm();
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