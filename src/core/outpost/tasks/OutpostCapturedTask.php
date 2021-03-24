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

class OutpostCapturedTask extends Task
{

	private $player;

	private $taskId;

	private $core;

	private $outpostManager;

	private $firstPosition;

	private $secondPosition;

	private $level;

	private $time = 100;

	public function __construct(CorePlayer $player)
	{

		$this->player = $player;

		$this->core = Urbis::getInstance();

		$this->outpostManager = $this->core->getOutpostManager();

		$this->firstPosition = $this->outpostManager->getFirstPosition();
		$this->secondPosition = $this->outpostManager->getSecondPosition();
		$this->level = $this->outpostManager->getLevel();

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

		if($this->time == 100)
		{

			$this->time = 0;

		}
		else
		{

			$this->time++;

			return;

		}

		if($this->isPositionInside($this->player->getPosition()) && $this->outpostManager->isCaptured() && $this->player->isOutpostCaptured() && $this->outpostManager->isRunning())
		{

			$this->core->getServer()->broadcastMessage("\n§6§lUr§ebis §r§8| §r§e{$this->player->getName()} §r§7has captured the §bOutpost §r§7and has been rewarded 17 Faction Power and 5,000 money! Type §b§l/outpost §r§7to go there!\n\n");

				if($this->player->getFaction() == null)
				{

					// DO NOTHING

				}
				else
				{

					$this->player->getFaction()->addStrength(17);

				}

				$this->player->addToBalance(5000);

				$this->outpostManager->setCaptured(true);
				$this->player->setOutpostCaptured(true);
				$this->player->setOutpostCaptureProgress(0);
				$this->player->setOutpostCapturing(false);

		}
		else
		{

			$this->outpostManager->setCaptured(false);
			$this->player->setOutpostCaptured(true);
			$this->player->setOutpostCaptureProgress(0);
			$this->player->setOutpostCapturing(false);

			$this->core->getScheduler()->cancelTask($this->getTaskId());

			return;

		}
	
	}

	/**
     * @param Position $position
     *
     * @return bool
     */
    public function isPositionInside(Position $position): bool {
        $level = $position->getLevel();
        $firstPosition = $this->firstPosition;
        $secondPosition = $this->secondPosition;
        $minX = min($firstPosition->getX(), $secondPosition->getX());
        $maxX = max($firstPosition->getX(), $secondPosition->getX());
        $minY = min($firstPosition->getY(), $secondPosition->getY());
        $maxY = max($firstPosition->getY(), $secondPosition->getY());
        $minZ = min($firstPosition->getZ(), $secondPosition->getZ());
        $maxZ = max($firstPosition->getZ(), $secondPosition->getZ());
        return $minX <= $position->getX() and $maxX >= $position->getX() and $minY <= $position->getY() and
            $maxY >= $position->getY() and $minZ <= $position->getZ() and $maxZ >= $position->getZ() and
            $this->level->getName() === $level->getName();
	}

}