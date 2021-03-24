<?php

declare(strict_types = 1);

namespace core\faction\command\subCommands;

use core\command\utils\SubCommand;
use core\faction\Faction;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use core\utils\UtilsException;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class TlSubCommand extends SubCommand {

    /**
     * JoinSubCommand constructor.
     */
    public function __construct() {
        parent::__construct("tl", "/faction tl");
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
		$faction = $sender->getFaction();
        if($sender->getFaction() !== null) {
			foreach($faction->getOnlineMembers() as $member){
            	$member->sendMessage("§8§l(§6§l!§8) §r§7It looks like §e" .  $sender->getName() . " §7needs help! XYZ:");
				return;
			}
        }
    }
}