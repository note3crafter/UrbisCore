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

class OutpostCaptureTask extends Task
{

	private $player;


	private $taskId;

	private $core;

	private $outpostManager;

	private $progress = 0;

	public function __construct(CorePlayer $player)
	{

		$this->player = $player;

		$this->core = Urbis::getInstance();

		$this->outpostManager = $this->core->getOutpostManager();

		$this->taskId = $this->getTaskId();

	}

	public function onRun(int $tick)
	{

		if($this->player === null)
		{

			return;

		}

		if(!$this->player->isOnline())
		{

			return;

		}

		if(!$this->outpostManager->isCaptured() && $this->outpostManager->isRunning() && $this->player->isOutpostCapturing() && !$this->player->isOutpostCaptured())
		{

			$this->progress++;

			$this->player->setOutpostCaptureProgress($this->progress);

			$this->player->sendPopup("§b§lOUTPOST§r§8: §r" . TextFormat::RED . $this->player->getOutpostCaptureProgress());

			if($this->player->getOutpostCaptureProgress() == 100)
			{

				$this->outpostManager->setCaptured(true);
				$this->player->setOutpostCaptured(true);
				$this->player->setOutpostCaptureProgress(0);
				$this->player->setOutpostCapturing(true);
				$this->outpostManager->unsetCaptures($this->player->getName());

				$this->core->getScheduler()->scheduleRepeatingTask(new OutpostCapturedTask($this->player), 20);

				$this->core->getScheduler()->cancelTask($this->getTaskId());

				return;

			}

		}
		else
		{

			$this->core->getScheduler()->cancelTask($this->getTaskId());

			return;

		}

	}

}
