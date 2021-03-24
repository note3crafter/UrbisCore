<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\rank\Rank;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\utils\TextFormat;

class LevelupCommand extends Command {

    /**
     * LevelupCommand constructor.
     */
    public function __construct() {
        parent::__construct("levelup", "Level up", "/levelup", ["lu"]);
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
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        switch($sender->addUrbisPoints()) {
            case $sender->getUrbisPoints(0):
                $price = 500000;
                $levelId = $sender->getUrbisPoints(1);
                $levelC = 1;
                break;
            case $sender->getUrbisPoints(2):
                $levelId = $sender->getUrbisPoints(3);
                $levelC = 1;
                break;
            default:
                if(!$sender->hasPermission("permission.levelss")) {
                    $price = 1500000;
                 } 
                 else {
                    $price = null;
                }
        }
        if($price > $sender->getMobCoins()) {
            $sender->sendMessage("§l§c»§r §7You need §2$price §3to level up!");
            return;
        }
        if(isset($levelId)) {
            $sender->subtractMobCoins($price);
            $sender->addUrbisPoints(1);
            $sender->addTitle("§aYOU HAVE LEVELED UP!");
            $sender->sendMessage("§l§a»§r §7You have now leveled up!");
            $sender->getLevel()->addSound(new AnvilUseSound($sender));
        }
        else {
            $sender->sendMessage(Translation::getMessage("errorOccurred"));
        }
    }
}