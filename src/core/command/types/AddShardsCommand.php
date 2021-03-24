<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class AddShardsCommand extends Command {

    /**
     * AddShardsCommand constructor.
     */
    public function __construct() {
        parent::__construct("addshards", "Add shards to a player's shards.", "/addshards <player> <amount>", ["giveshards"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender->isOp()) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        if(!isset($args[1])) {
            $sender->sendMessage(Translation::getMessage("usageMessage", [
                "usage" => $this->getUsage()
            ]));
            return;
        }
        $player = $this->getCore()->getServer()->getPlayer($args[0]);
        if(!$player instanceof CorePlayer) {
            $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT shards FROM players WHERE username = ?");
            $stmt->bind_param("s", $args[0]);
            $stmt->execute();
            $stmt->bind_result($shards);
            $stmt->fetch();
            $stmt->close();
            if($shards === null) {
                $sender->sendMessage(Translation::getMessage("invalidPlayer"));
                return;
            }
        }
        if(!is_numeric($args[1])) {
            $sender->sendMessage(Translation::getMessage("notNumeric"));
            return;
        }
        if(isset($shards)) {
            $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("UPDATE players SET shards = shards + ? WHERE username = ?");
            $stmt->bind_param("is", $args[1], $args[0]);
            $stmt->execute();
            $stmt->close();
        }
        else {
            /** @var CorePlayer $player */
            $player->addShards((int)$args[1]);
        }
        $sender->sendMessage(Translation::getMessage("addShardsSuccess", [
            "amount" => TextFormat::GREEN . "" . $args[1],
            "name" => TextFormat::GOLD . $player instanceof CorePlayer ? $player->getName() : $args[0]
        ]));
    }
}