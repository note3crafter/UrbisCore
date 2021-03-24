<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\forms\KitListForm;
use core\command\utils\Command;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;

class GKitCommand extends Command {

    /** @var KitListForm */
    private $form;

    /**
     * GKitCommand constructor.
     */
    public function __construct() {
        parent::__construct("gkit", "Manage your god kits.");
        $kits = $this->getCore()->getKitManager()->getGodlyKits();
        $this->form = new KitListForm($kits);
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
