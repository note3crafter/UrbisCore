<?php



namespace core\bounty\command\subCommands;



use core\command\utils\SubCommand;

use core\CorePlayer;

use core\Urbis;

use core\translation\Translation;

use core\translation\TranslationException;

use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat;

use core\bounty\forms\ListForm;



class ListSubCommand extends SubCommand {



	/**

	 * ListSubCommand Constructor

	 */

	public function __construct()

	{

		parent::__construct('list', '/bounty list');

	}



	public function execute(CommandSender $sender, string $label, array $args): void {



		if(!$sender instanceof CorePlayer){

			return;

		}



		$sender->sendForm(new ListForm());



	}



}

