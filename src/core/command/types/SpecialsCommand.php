<?php

declare(strict_types = 1);

namespace core\command\types;

use core\CorePlayer;
use core\Urbis;
use core\command\invmenuforms\SpecialsMenu;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SpecialsCommand extends Command {


    /**
     * SpecialsCommand constructor.
     */
    public function __construct() {
        parent::__construct("specials", "Purchase items with mob coins or shards in this shop.");
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
        $menu = new SpecialsMenu($sender);

        $menu->sendMenu();
    }
}