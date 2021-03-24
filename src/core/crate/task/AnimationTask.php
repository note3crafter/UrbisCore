<?php

declare(strict_types = 1);

namespace core\crate\task;

use core\crate\Crate;
use core\crate\Reward;
use core\Urbis;
use core\CorePlayer;
use core\utils\FloatingTextParticle;
use pocketmine\entity\Entity;
use pocketmine\level\particle\LavaParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddItemActorPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\scheduler\Task;

class AnimationTask extends Task {

    /** @var int */
    private $runs = 0;

    /** @var Crate */
    private $crate;

    /** @var CorePlayer */
    private $player;

    /** @var int */
    private $id;

    /** @var FloatingTextParticle */
    private $ftp;

    /**
     * @var int
     */
    private $count;

    /**
     * @var Reward[]
     */
    private $rewards = [];

    /**
     * AnimationTask constructor.
     *
     * @param Crate $crate
     * @param CorePlayer $player
     * @param int $count
     */
    public function __construct(Crate $crate, CorePlayer $player, int $count) {

        $this->crate = $crate;
        $player->setRunningCrateAnimation();
        $this->player = $player;
        $this->count = $count;
    }

    /**
     * @param Reward $reward
     */
    public function spawnItemEntity(Reward $reward) {
        $this->id = Entity::$entityCount++;
        $pk = new AddItemActorPacket();
        $pk->item = $reward->getItem();
        $pk->position = $this->crate->getPosition()->add(0.5, 0.75, 0.5);
        $pk->entityRuntimeId = $this->id;
        $this->player->dataPacket($pk);
    }

    public function removeItemEntity() {
        $pk = new RemoveActorPacket();
        $pk->entityUniqueId = $this->id;
        $this->player->dataPacket($pk);
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) {
        if($this->player->isClosed()) {
            Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        ++$this->runs;
        $position = $this->crate->getPosition();
        if($this->runs === 1) {
            $pk = new LevelSoundEventPacket();
            $pk->position = $position;
            $pk->sound = LevelSoundEventPacket::SOUND_CHEST_OPEN;
            $this->player->sendDataPacket($pk);
            $pk = new BlockEventPacket();
            $pk->x = $position->getFloorX();
            $pk->y = $position->getFloorY();
            $pk->z = $position->getFloorZ();
            $pk->eventType = 1;
            $pk->eventData = 1;
            $this->player->sendDataPacket($pk);
            return;
        }
        if($this->runs === 2) {
            $pk = new LevelSoundEventPacket();
            $pk->position = $position;
            $pk->sound = LevelSoundEventPacket::SOUND_LAUNCH;
            $this->player->sendDataPacket($pk);
        }
        if($this->runs === 4) {
            $cx = $position->getX() + 0.5;
            $cy = $position->getY() + 1.2;
            $cz = $position->getZ() + 0.5;
            $radius = 1;
            for($i = 0; $i < 21; $i += 1.1){
                $x = $cx + ($radius * cos($i));
                $z = $cz + ($radius * sin($i));
                $pos = new Vector3($x, $cy, $z);
                $position->level->addParticle(new LavaParticle($pos), [$this->player]);
            }

            for($i = 0; $i < $this->count; $i++) {
                $this->rewards[] = $this->crate->getReward();
            }
            $bestReward = $this->getMostValuableReward();
            foreach($this->rewards as $reward) {
                $callable = $reward->getCallback();
                $callable($this->player);
            }

            $pk = new LevelSoundEventPacket();
            $pk->position = $position;
            $pk->sound = LevelSoundEventPacket::SOUND_BLAST;
            $this->player->sendDataPacket($pk);
            $this->spawnItemEntity($bestReward);
            //$this->spawnItemName($bestReward);
            $this->crate->showReward($bestReward, $this->player);
            return;
        }
        if($this->runs === 7) {
            $pk = new LevelSoundEventPacket();
            $pk->position = $position;
            $pk->sound = LevelSoundEventPacket::SOUND_CHEST_CLOSED;
            $this->player->sendDataPacket($pk);
            $pk = new BlockEventPacket();
            $pk->x = $position->getFloorX();
            $pk->y = $position->getFloorY();
            $pk->z = $position->getFloorZ();
            $pk->eventType = 1;
            $pk->eventData = 0;
            $this->player->sendDataPacket($pk);
            $this->removeItemEntity();
            //$this->despawnItemName();
            $this->crate->updateTo($this->player);
            $this->player->setRunningCrateAnimation(false);
            Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }

    public function getMostValuableReward() : ?Reward {
        if(empty($this->rewards)) return null;

        // Sort by chance
        usort($this->rewards, function($a, $b) {
           return $a->getChance() < $b->getChance() ? -1 : 1;
        });

        return $this->rewards[0];
    }

    public function spawnItemName(?Reward $bestReward) {
        $position = $this->crate->getPosition();
        $position->y = $position->y + 1.25;

        $this->ftp = new FloatingTextParticle($position, (string) rand(0, PHP_INT_MAX >> 8), $bestReward->getName());
        $this->ftp->spawn($this->player);
    }

    public function despawnItemName() {
        if($this->ftp instanceof FloatingTextParticle) {
            $this->ftp->despawn($this->player);
        }
    }

}