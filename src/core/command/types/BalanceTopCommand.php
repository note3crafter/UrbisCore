<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BalanceTopCommand extends Command {

    /**
     * BalanceTopCommand constructor.
     */
    public function __construct() {
        parent::__construct("balancetop", "Show the richest players.", "/balancetop <page>", ["baltop", "topmoney"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        $page = 1;
        if(isset($args[0])) {
            $page = $args[0];
        }
        if((!is_numeric($page)) or $page < 1) {
            $page = 1;
        }
        $place = (($page - 1) * 5);
        $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT username, balance FROM players ORDER BY balance DESC LIMIT 5 OFFSET " . $place);
        $stmt->execute();
        $stmt->bind_result($name, $balance);
        ++$place;
        $text = $text = "§l§cRICHEST PLAYERS §r §7Page $page";
        while($stmt->fetch()) {
            $text .= "\n§l§e" . $place . "§6.§r§7" . $name . " §l§8|§r §2$" . "§a" . number_format($balance);
            $place++;
        }
        $stmt->close();
        $sender->sendMessage($text);
    }
}