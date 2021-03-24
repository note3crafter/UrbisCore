<?php

namespace core\outpost\tasks;

use core\Urbis;
use core\CorePlayer;
use core\outpost\Outpost;
use core\outpost\OutpostManager;
use core\translation\Translation;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class OutpostTicker extends Task
{

	private $outpostManager;

	public function __construct($outpostManager)
	{

		$this->outpostManager = $outpostManager;

	}

	public function onRun(int $tick)
	{

		if($this->outpostManager->isRunning())
		{

			$level = Urbis::getInstance()->getServer()->getLevelByName("koth");

			$list = null;

			foreach($this->outpostManager->getCaptures() as $player)
			{

				$list .= "§e" . $player->getName() . "§r§8 | §r§3" . $player->getOutpostCaptureProgress() . "§r\n";

			}

			foreach($level->getPlayers() as $player)
			{

				if($player->getFloatingText("Outpost") !== null)
				{

					$player->removeFloatingText("Outpost");

				}

				$player->addFloatingText(new Position(-86.5, 17, 120.5, $level), "Outpost", "§3§l§kIII§r§4§l OUTPOST §r§3§l§kIII§r\n" . $list);

			}

		}
		else
		{

			foreach(Elemental::getInstance()->getServer()->getLevelByName("koth")->getPlayers() as $player)
			{

				$player->removeFloatingText("Outpost");

			}

			Elemental::getInstance()->getScheduler()->cancelTask($this->getTaskId());

		}

	}

}