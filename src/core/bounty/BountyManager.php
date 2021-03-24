<?php



namespace core\bounty;



use core\Urbis;

use core\CorePlayer;

use core\translation\Translation;

use pocketmine\entity\Entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\level\Position;

use pocketmine\math\Vector3;

use pocketmine\math\AxisAlignedBB;

use pocketmine\Player;

use pocketmine\Server;

use pocketmine\scheduler\TaskScheduler;

use pocketmine\utils\TextFormat;



use core\bounty\Bounty;

use core\bounty\BountyCommand;



class BountyManager implements Listener

{



	public function __construct()

	{



		$this->core = Urbis::getInstance();



	}



	public function onDeath(PlayerDeathEvent $event) {



		$player = $event->getPlayer();



		if(!$player instanceof CorePlayer) {

			return;

		}



		$cause = $player->getLastDamageCause();



		if($cause instanceof EntityDamageByEntityEvent) {



			$killer = $cause->getDamager();



			if($killer instanceof CorePlayer) {



				$killer->addToBalance($player->getBounty());



				if($player->getBounty() >= 5000) {

					$this->core->getServer()->broadcastMessage("§8§l(§b!§8) §r§b" . $killer->getName() . " §r§7has claimed §b" . $player->getName(). "'s §r§2$" . "§a" . $player->getBounty() . " §r§7bounty!");

				}



				$player->setBounty(0);



			}

		}



	}



}