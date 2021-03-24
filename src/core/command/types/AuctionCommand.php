<?php

declare(strict_types = 1);

namespace core\command\types;

use core\auction\invforms\MainAuctionForm;
use core\command\utils\Command;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AuctionCommand extends Command {

    /**
     * AuctionCommand constructor.
     */
    public function __construct() {
        parent::__construct("auctionhouse", "Open auctionhouse UI", "/auctionhouse");
        $this->setAliases(["ah"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) return;
        $sender->sendMessage("Sorry , Auction House has been disabled due to incomplete and unfinished ah.");
    }
}