<?php



namespace core\classes;

use core\Urbis;

use core\CorePlayer;

use core\translation\Translation;

use pocketmine\entity\Entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\level\Position;

use pocketmine\math\Vector3;

use pocketmine\math\AxisAlignedBB;

use pocketmine\Player;

use pocketmine\Server;

use pocketmine\scheduler\TaskScheduler;

use pocketmine\utils\TextFormat;



use core\classes\menu\ClassesMenu;



class ClassManager implements Listener {



	public function __construct()

	{



		$this->core = Urbis::getInstance();



	}



	public function onJoin(PlayerJoinEvent $event) {

		$player = $event->getPlayer();



		if($player->getClass() === null) {



			$menu = new ClassesMenu($player);



			$menu->sendMenu($player);



			return;

		} else {

			return;

		}



	}



}

