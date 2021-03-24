<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ShardsTopCommand extends Command {

    /**
     * ShardsTopCommand constructor.
     */
    public function __construct() {
        parent::__construct("shardstop", "Show the richest players.", "/shardstop <page>", ["shardstop", "topshards"]);
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
        $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT username, shards FROM players ORDER BY shards DESC LIMIT 5 OFFSET " . $place);
        $stmt->execute();
        $stmt->bind_result($name, $balance);
        ++$place;
        $text = $text = "§l§9RICHEST SHARDS PLAYERS §r §7Page $page";
        while($stmt->fetch()) {
            $text .= "\n§l§e" . $place . "§6.§r§7" . $name . " §l§8|§r " . "§3" . number_format($balance);
            $place++;
        }
        $stmt->close();
        $sender->sendMessage($text);
    }
}