<?php

namespace core\cannons\tasks;

use core\cannons\entity\CannonEntity;
use core\Urbis;
use pocketmine\scheduler\Task;

class AutoFiringTask extends Task{

    private $cannon;

    public function __construct(CannonEntity $entity)
    {
        $this->cannon = $entity;
    }

    /**
     * @return void
     */
    public function onRun(int $currentTick)
    {
        if (!$this->cannon->fireShot()){
            $this->cannon->setAutoFiringTaskId(null);
            Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}
