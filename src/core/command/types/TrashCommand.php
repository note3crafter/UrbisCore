<?php

declare(strict_types = 1);

namespace core\command\types;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TrashCommand extends Command {

    /**
     * TrashCommand constructor.
     */
    public function __construct() {
        parent::__construct("bin", "Put all of your useless items in here and help the environment!", null, ["trash"]);
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
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->setName("§cTrash Bin§r");
        $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory) {
            $inventory->clearAll();
        });
        $menu->send($sender);
    }
}
