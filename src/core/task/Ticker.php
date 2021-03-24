<?php


namespace core\task;


use core\Urbis;
use pocketmine\scheduler\Task;
use core\traits\Tickable;

class Ticker extends Task
{

    /**
     * @var Tickable
     */
    private $tickable;

    public function __construct(Tickable $tickable)
    {
        $this->tickable = $tickable;
    }

    /**
     * @inheritDoc
     */
    public function onRun(int $currentTick)
    {
        $this->tickable->tick($currentTick);
    }

    public function cancel(int $after = -1) : void {
        if(($instance = Urbis::getInstance())) {
            if($after > 0) {
                $task = $this;
                $instance->getScheduler()->scheduleDelayedTask(new DelayedClosureTask(function(int $currentTick) use ($task) {
                    $task->cancel();
                }), $after);
            } else {
                $instance->getScheduler()->cancelTask($this->getTaskId());
            }
        }
    }

}