<?php



declare(strict_types=1);



namespace core\bounty\command;



use core\command\utils\Command;

use core\Urbis;

use core\CorePlayer;

use core\translation\Translation;

use pocketmine\command\CommandSender;

use pocketmine\network\mcpe\protocol\AnimatePacket;

use pocketmine\utils\TextFormat;

use core\command\task\TeleportTask;

use pocketmine\level\Position;

use pocketmine\Server;

use pocketmine\level\Level;



use core\bounty\command\subCommands\AddSubCommand;

use core\bounty\command\subCommands\ListSubCommand;



class BountyCommand extends Command

{



	public function __construct()

	{



		parent::__construct('bounty', 'Manage bounties', '/bounty <add/list>', ['bounties', "bt"]);



		$this->addSubCommand(new AddSubCommand());

		$this->addSubCommand(new ListSubCommand());



	}



	public function execute(CommandSender $sender, string $label, array $args): void {



		if(!$sender instanceof CorePlayer) {

			return;

		}



		if(isset($args[0])) {



			$subCommand = $this->getSubCommand($args[0]);

			

			if($subCommand !== null){



				$subCommand->execute($sender, $label, $args);



				return;

			}



			$sender->sendMessage(Translation::getMessage("usageMessage", [

				"usage" => $this->getUsage()

			]));



			return;



		}



		$sender->sendMessage(Translation::getMessage("usageMessage", [

			"usage" => $this->getUsage()

		]));



	}



}