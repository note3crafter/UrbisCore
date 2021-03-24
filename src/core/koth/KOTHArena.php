<?php

namespace core\koth;

use core\crate\Crate;
use core\Urbis;
use core\CorePlayer;
use core\item\types\CrateKeyNote;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;

class KOTHArena {
  
    /** @var string */
    private $name;

    /** @var Position */
    private $firstPosition;

    /** @var Position */
    private $secondPosition;

    /** @var null|CorePlayer */
    private $capturer = null;

    /** @var int */
    private $captureProgress = 0;

    /** @var int */
    private $objectiveTime;

    /**
     * KOTHArena constructor.
     *
     * @param string $name
     * @param Position $firstPosition
     * @param Position $secondPosition
     * @param int $objectiveTime
     *
     * @throws KOTHException
     */
    public function __construct(string $name, Position $firstPosition, Position $secondPosition, int $objectiveTime) {
        $this->name = $name;
        $this->firstPosition = $firstPosition;
        $this->secondPosition = $secondPosition;
        if($firstPosition->getLevel() === null or $secondPosition->getLevel() === null) {
            throw new KOTHException("KOTH arena \"$name\" position levels are invalid.");
        }
        if($firstPosition->getLevel()->getName() !== $secondPosition->getLevel()->getName()) {
            throw new KOTHException("KOTH arena \"$name\" position levels are not the same.");
        }
        $this->objectiveTime = $objectiveTime;
    }

    /**
     * @throws TranslationException
     */
    public function tick(): void {
        if($this->captureProgress >= $this->objectiveTime) {
            if(!$this->capturer->isOnline()) {
                $this->captureProgress = 0;
                $this->capturer = null;
                return;
            }
            $this->capturer->getInventory()->addItem(new CrateKeyNote($this->core->getCrateManager()->getCrate(Crate::COMMON), 5))->getItemForm()->setCount(ceil($this->objectiveTime / 300));
            Urbis::getInstance()->getKOTHManager()->endGame();
            Urbis::getInstance()->getServer()->broadcastMessage("§l§b" . $this->capturer->getName() . " §r§7has won the §l§d" . $this->name . " §r§7KOTH!!");
        }
        if($this->capturer === null or (!$this->isPositionInside($this->capturer)) or (!$this->capturer->isOnline())) {
            $this->captureProgress = 0;
            $this->capturer = null;
            foreach($this->firstPosition->getLevel()->getPlayers() as $player) {
                if(!$player instanceof CorePlayer) {
                    continue;
                }
                if($this->isPositionInside($player) and $player->isInStaffMode() === false) {
                    if($this->capturer !== null) {
                        return;
                    }
                    $this->capturer = $player;
                }
            }
            if($this->capturer !== null) {
                Urbis::getInstance()->getServer()->broadcastMessage("§l§b" . $this->capturer->getName() . " §r§7is currently capturing §l§d" . $this->name . " §r§7KoTH at §l§b/outpost");
                $player->sendTitle("§aCAPTURING");
            }
        }
        $this->captureProgress++;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param CorePlayer|null $player
     */
    public function setCapturer(?CorePlayer $player = null): void {
        $this->capturer = $player;
    }

    /**
     * @return CorePlayer|null
     */
    public function getCapturer(): ?CorePlayer {
        return $this->capturer;
    }

    /**
     * @param int $amount
     */
    public function setCaptureProgress(int $amount): void {
        $this->captureProgress = $amount;
    }

    /**
     * @return int
     */
    public function getCaptureProgress(): int {
        return $this->captureProgress;
    }

    /**
     * @return int
     */
    public function getObjectiveTime(): int {
        return $this->objectiveTime;
    }

    /**
     * @return Position
     */
    public function getFirstPosition(): Position {
        return $this->firstPosition;
    }

    /**
     * @return Position
     */
    public function getSecondPosition(): Position {
        return $this->secondPosition;
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
        $minZ = min($firstPosition->getZ(), $secondPosition->getZ());
        $maxZ = max($firstPosition->getZ(), $secondPosition->getZ());
        return $minX <= $position->getX() and $maxX >= $position->getFloorX() and
            $minZ <= $position->getZ() and $maxZ >= $position->getFloorZ() and
            $this->firstPosition->getLevel()->getName() === $level->getName();
    }

}