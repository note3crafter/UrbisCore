<?php

declare(strict_types = 1);

namespace core\command\types;

use core\entity\forms\VoteShopForm;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class VoteShopCommand extends Command {

    /** @var VoteShopForm */
    private $form;

    /**
     * VoteShopCommand constructor.
     */
    public function __construct() {
        parent::__construct("voteshop", "Purchase different items with the vote your vote points.");
        $this->form = new VoteShopForm();
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