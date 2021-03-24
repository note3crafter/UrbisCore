<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\item\types\Artifact;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class SacredAllCommand extends Command {

    /**
     * SacredAllCommand constructor.
     */
    public function __construct() {
        parent::__construct("artifactall", "Give artifacts to all players.", "/artifactall <amount>");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($sender instanceof ConsoleCommandSender or $sender->isOp()) {
            if(!isset($args[0])) {
                $sender->sendMessage(Translation::getMessage("usageMessage", [
                    "usage" => $this->getUsage()
                ]));
                return;
            }
            if(!$sender instanceof ConsoleCommandSender) {
                if(!$sender->isOp()) {
                    $sender->sendMessage("§l§c»§r §7You just got caught §c§lLACKING! §r§7Only someone under the name of §eXekVMXx §r§7can use this command!");
                    return;
                }
            }
            $amount = is_numeric($args[0]) ? (int)$args[0] : 1;
            $item = (new Artifact())->getItemForm()->setCount($amount);
            $this->getCore()->getServer()->broadcastMessage(Translation::getMessage("artifactAll", [
                "name" => TextFormat::AQUA . $sender->getName(),
                "amount" => TextFormat::YELLOW . $amount,
            ]));
            foreach($this->getCore()->getServer()->getOnlinePlayers() as $player) {
                if($player->getInventory()->canAddItem($item)) {
                    $player->getInventory()->addItem($item);
                }
            }
            return;
        }
        $sender->sendMessage(Translation::getMessage("noPermission"));
        return;
    }
}