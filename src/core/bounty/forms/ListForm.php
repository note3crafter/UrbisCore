<?php



declare(strict_types = 1);



namespace core\bounty\forms;



use core\Urbis;

use core\libs\form\CustomForm;

use core\libs\form\element\Label;



use pocketmine\utils\TextFormat;



class ListForm extends CustomForm {



    /**

     * BountyListForm constructor.

     */

    public function __construct() {

        $title = TextFormat::BOLD . TextFormat::DARK_RED . "Most Wanted" . TextFormat::RESET;

		$elements = [];



		$stmt = Urbis::getInstance()->getMySQLProvider()->getDatabase()->prepare("SELECT username, bounty FROM players ORDER BY bounty DESC LIMIT 30");

		$stmt->execute();

		$stmt->bind_result($username, $bounty);



		$place = 1;

		$text = $text = "";

		

		while($stmt->fetch()){

			$text .= "\n" . "§6§l#§e" . $place . " §r§b" . $username . " §r§e| §r§2$" . "§a" . $bounty;

			$place++;

		}



		$elements[] = new Label("MostWantedPlayersList", $text);

        parent::__construct($title, $elements);

    }

}