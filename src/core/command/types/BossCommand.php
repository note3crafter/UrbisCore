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

class BossCommand extends Command {

    /**
     * BossCommand constructor.
     */
    public function __construct() {
        parent::__construct("boss", "Teleport to the boss arena.");
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
        	if(isset($args[0]) && $sender->isOp()){
        		if($args[0] == "spawn"){
					Urbis::getInstance()->getCombatManager()->summonRandBoss();
					$sender->sendMessage("§l§a»§r §7Successfully summoned a random boss.");
				}else{
					$sender->sendMessage("§l§c»§r §7Invalid argument given.");
				}
			}else{
				$position = Urbis::getInstance()->bossData->get("spawn");
				$level = $sender->getServer()->getLevelByName($position["level"]);
				if($level !== null) {
					Server::getInstance()->loadLevel($position["level"]);
					$spawn = new Position($position["x"], $position["y"], $position["z"], $level);
					Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($sender, $spawn, 5), 20);
					return;
				}
				$sender->sendMessage(Translation::RED."Failed to teleport at Boss arena!");
			}
            return;
        }
        $sender->sendMessage(Translation::getMessage("noPermission"));
        return;
    }
}