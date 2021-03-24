<?php

namespace core\outpost;


use core\Urbis;
use core\CorePlayer;
use core\outpost\tasks\OutpostCaptureTask;
use core\outpost\tasks\OutpostTicker;
use core\translation\Translation;
use core\utils\FloatingTextParticle;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class OutpostManager implements Listener
{

	private $level;

	private $firstPosition;

	private $secondPosition;

	private $isRunning;

	private $isCaptured;

	private $captures;

	public function __construct()
	{

		Urbis::getInstance()->getServer()->getPluginManager()->registerEvents($this, Urbis::getInstance());
		Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new OutpostTicker($this), 3 * 20);

		$this->level = Urbis::getInstance()->getServer()->getLevelByName("koth");

		$this->firstPosition = new Position(-88.5, 13, 118.5, $this->level);
		$this->secondPosition = new Position(-84.5, 17, 122.5, $this->level);

		$this->isRunning = true;

		$this->isCaptured = false;

		$this->captures = [];

	}

	public function setRunning(bool $value)
	{

		$this->isRunning = $value;

		return;

	}

	public function isRunning()
	{

		return $this->isRunning;

	}

	public function setCaptured(bool $value)
	{

		$this->isCaptured = $value;

		return;

	}

	public function isCaptured()
	{

		return $this->isCaptured;

	}

	public function getLevel()
	{

		return $this->level;

	}

	public function getFirstPosition()
	{

		return $this->firstPosition;

	}

	public function getSecondPosition()
	{

		return $this->secondPosition;

	}

	public function getCaptures()
	{

		return $this->captures;

	}

	public function setCaptures($username, $value)
	{

		$this->captures[$username] = $value;

	}

	public function unsetCaptures($username)
	{

		unset($this->captures[$username]);

	}

	public function onPlayerMove(PlayerMoveEvent $event)
	{

		$player = $event->getPlayer();

		if(!$player instanceof CorePlayer)
		{

			return;

		}

		$level = $player->getLevel();

		$position = $player->getPosition();

		if($this->isPositionInside($position) && $this->isRunning() && !$player->isOutpostCapturing() && !$this->isCaptured())
		{

			$player->setOutpostCapturing(true);
			$player->setOutpostCaptured(false);

			$this->captures[$player->getName()] = $player;

			Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new OutpostCaptureTask($player), 20);

			Urbis::getInstance()->getLogger()->info($player->getName() . " has started capturing Outpost!");

			return;

		}

		if(!$this->isPositionInside($position) && $this->isRunning() && $player->isOutpostCapturing())
		{

			$player->setOutpostCapturing(false);
			$player->setOutpostCaptured(false);
			$this->setCaptured(false);
			$player->setOutpostCaptureProgress(0);
			$this->unsetCaptures($player->getName());

			Urbis::getInstance()->getLogger()->info($player->getName() . " has stopped capturing Outpost!");

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