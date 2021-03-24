<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\item\types\BossEgg;
use core\item\types\HolyBox;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

class GiveItemCommand extends Command {

    /**
     * GiveItemCommand constructor.
     */
    public function __construct() {
        parent::__construct("giveitem", "Give item to a player.", "/giveitem <player> <item>");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (($sender instanceof CorePlayer && $sender->isOp()) || $sender instanceof ConsoleCommandSender) {
            if(!isset($args[1])) {
                $sender->sendMessage(Translation::getMessage("usageMessage", [
                    "usage" => $this->getUsage()
                ]));
                return;
            }
            $player = $this->getCore()->getServer()->getPlayer($args[0]);
            if(!$player instanceof CorePlayer) {
                $sender->sendMessage(Translation::getMessage("invalidPlayer"));
                return;
            }
            switch($args[1]) {
                case "artisticbox":
                    if(!isset($args[2])) {
                        $kits = $this->getCore()->getKitManager()->getSacredKits();
                        $kit = $kits[array_rand($kits)];
                    }
                    else {
                        $kit = $this->getCore()->getKitManager()->getKitByName($args[2]);
                    }
                    if($kit === null) {
                        $sender->sendMessage(Translation::getMessage("invalidKit"));
                    }
                    $player->getInventory()->addItem((new HolyBox($kit))->getItemForm());
                    $sender->sendMessage("§l§a»§r §7You received a Meta Box!");
                    break;
                case "boss":
                    if(!isset($args[2])) {
                        $sender->sendMessage(Translation::getMessage("usageMessage", [
                            "usage" => "/giveitem <player> boss <type>"
                        ]));
                        return;
                    }
                    $boss = $this->getCore()->getCombatManager()->getBossNameByIdentifier((int)$args[2]);
                    if($boss === null) {
                        $sender->sendMessage(Translation::getMessage("invalidBoss"));
                        return;
                    }
                    $boss = $this->getCore()->getCombatManager()->getIdentifierByName($boss);
                    $player->getInventory()->addItem((new BossEgg($boss))->getItemForm());
                    $sender->sendMessage("§l§a»§r §7You received a boss egg!");
                    break;
                default:
                    $types = ["artisticbox", "boss", "kit"];
                    $sender->sendMessage("§l§c»§r §7Unknown item: $args[1]");
                    $sender->sendMessage("§l§6»§r §7Available items:§r"." ".implode("§r, ", $types));
                    break;
            }
            return;
        }
        $sender->sendMessage(Translation::getMessage("noPermission"));
    }
}