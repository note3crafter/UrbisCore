<?php

namespace core\entity\task;

use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;

class DespawnTask extends Task{

    private $entity;
    /**
     * Actions to execute when run
     *
     * @return void
     */

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function onRun(int $currentTick)
    {
        if ($this->entity->isOnGround()){
            $this->entity->flagForDespawn();
        }
    }
}