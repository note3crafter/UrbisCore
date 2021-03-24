<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\Urbis;
use core\command\task\TeleportTask;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class OutpostCommand extends Command {

    /**
     * OutpostCommand constructor.
     */
    public function __construct() {
        parent::__construct("outpost", "Teleports to outpost world", "/outpost");
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
            $sender->sendMessage("§l§c»§r §7Your not allowed to use this command");
            return;
        }
        $level = $sender->getServer()->getLevelByName("koth");
        if($level !== null) {
            Server::getInstance()->loadLevel("koth");
            $spawn = new Position(-90, 10, -59, $level);
            Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($sender, $spawn, 5), 20);
            return;
        }
    }
}