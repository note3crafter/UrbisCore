<?php

declare(strict_types = 1);

namespace core\faction\command\subCommands;

use core\command\utils\SubCommand;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TopSubCommand extends SubCommand {

    /**
     * TopSubCommand constructor.
     */
    public function __construct() {
        parent::__construct("top", "/faction top <money/power>");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!isset($args[1])) {
            $sender->sendMessage(Translation::getMessage("usageMessage", [
                "usage" => $this->getUsage()
            ]));
            return;
        }
        switch($args[1]) {
            case "money":
                $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT name, balance FROM factions ORDER BY balance DESC LIMIT 10");
                $stmt->execute();
                $stmt->bind_result($name, $balance);
                $place = 1;
                $text = $text = "§l§cTOP 10 RICHEST FACTIONS";
                while($stmt->fetch()) {
                    $text .= "\n§e" . $place . "§6. §r§7" . $name . " §l§8|§r §2$" . "§e" . number_format($balance);
                    $place++;
                }
                $stmt->close();
                $sender->sendMessage($text);
                break;
            case "power":
                $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT name, strength FROM factions ORDER BY strength DESC LIMIT 10");
                $stmt->execute();
                $stmt->bind_result($name, $strength);
                $place = 1;
                $text = $text = "§l§cTOP 10 STRONGEST FACTIONS";
                while($stmt->fetch()) {
                    $text .= "\n§e" . $place . "§6. §r§7" . $name . " §l§8|§r §c" . $strength . " §4STR";
                    $place++;
                }
                $stmt->close();
                $sender->sendMessage($text);
                break;
            default:
                $sender->sendMessage(Translation::getMessage("usageMessage", [
                    "usage" => $this->getUsage()
                ]));
                return;
                break;
        }
    }
}