<?php


namespace core\faction\command\subCommands;


use core\command\utils\Command;
use core\command\utils\SubCommand;
use core\faction\Faction;
use core\faction\FactionException;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ForceLeaderCommand extends SubCommand
{

	public function __construct()
	{
		parent::__construct("forceleader", "/faction forceleader <faction>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		if ($sender->isOp() and $sender instanceof CorePlayer) {
			if (!isset($args[1])) {
				$sender->sendMessage(Translation::getMessage("usageMessage", [
					"usage" => $this->getUsage()
				]));
				return;
			}
			$faction = $args[1];
			if ($this->getCore()->getFactionManager()->getFaction($faction) === null) {
				$sender->sendMessage(Translation::getMessage("invalidFaction"));
				return;
			}
			if ($sender->getFaction() == null) {
                $this->getCore()->getFactionManager()->getFaction($faction)->addMember($sender);
                $sender->setFactionRole(Faction::LEADER);
			}
		} else {
			$sender->sendMessage(Translation::getMessage("noPermission"));
		}
	}
}