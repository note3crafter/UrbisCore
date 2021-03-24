<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\invmenuforms\EnchantsMenu;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CEShopCommand extends Command {

    /**
     * CEShopCommand constructor.
     */
    public function __construct() {
        parent::__construct("ceshop", "Purchase enchantment books for custom enchants.");
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
        $menu = new EnchantsMenu($sender);

        $menu->sendMenu();
    }
}