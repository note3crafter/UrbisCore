<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\task\TeleportTask;
use core\command\utils\Command;
use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use http\Message;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Server;

class PvPCommand extends Command {

    /**
     * PvPCommand constructor.
     */
    public function __construct() {
        parent::__construct("pvp", "Teleport to a pvp arena");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($sender instanceof CorePlayer) {
            $level = $sender->getServer()->getLevelByName("SARENA");
            if($level !== null) {
                Server::getInstance()->loadLevel("SARENA");
                $spawn = new Position(325.1, 96.5, 210.7, $level);
                Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($sender, $spawn, 5), 20);
                return;
            }
            $sender->sendMessage("§l§c»§r §7Failed to teleport at arena!");
        }
        $sender->sendMessage(Translation::getMessage("noPermission"));
        return;
    }
}