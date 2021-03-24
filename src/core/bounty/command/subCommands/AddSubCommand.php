<?php



namespace core\bounty\command\subCommands;



use core\command\utils\SubCommand;

use core\CorePlayer;

use core\Urbis;

use core\translation\Translation;

use core\translation\TranslationException;

use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat;



class AddSubCommand extends SubCommand {



	/**

	 * AddSubCommand Constructor

	 */

	public function __construct()

	{

		parent::__construct('add', '/bounty add <player/username> <amount>');

	}



	public function execute(CommandSender $sender, string $label, array $args): void {



		if(!$sender instanceof CorePlayer){

			return;

		}



		if(!isset($args[1]) || !isset($args[2])) {

			$sender->sendMessage(Translation::getMessage("usageMessage", [

				"usage" => $this->getUsage()

			]));

			return;

		}



		$player = Urbis::getInstance()->getServer()->getPlayer($args[1]);

		if(!$player instanceof CorePlayer) {

			$sender->sendMessage(Translation::getMessage("invalidPlayer"));

			return;

		}



		if(!is_numeric($args[2])) {

			$sender->sendMessage(Translation::getMessage("notNumeric"));

			return;

		}



		if($args[2] < 5000) {

			$sender->sendMessage("§8§l(§c!§8) §r§7You must set a bounty of §b5000 §7or above.");

			return;

		}



		if($player->getName() == $sender->getName()) {

			$sender->sendMessage(Translation::getMessage("invalidPlayer"));

			return;

		}

		

		if($sender->getBalance() >= $args[2])

		{

		    

		    // Continue with execution

		    

		} else

		{

		    

		    $sender->sendMessage("§8§l(§c!§8) §r§7You don't have the much money!");

		    

		    return;

		    

		}



		$sender->subtractFromBalance($args[2]);

		$player->addBounty($args[2]);

		$sender->sendMessage("§8§l(§a!§8) §r§7You have successfully added a §2$" . "§a" . $args[2] . " §r§7bounty on §b" . $player->getName() . " !");

		$player->sendMessage("§8§l(§c!§8) §r§b" . $sender->getName() . " §r§7has added a §2$" . "§a" . $args[2] . " §r§7bounty on you!");

		Urbis::getInstance()->getServer()->broadcastMessage("§8§l(§b!§8) §r§b" . $sender->getName() . " §r§7has added a §2$" . "§a" . $args[2] . " §r§7bounty on §b" . $player->getName() . "!");



	}



}

