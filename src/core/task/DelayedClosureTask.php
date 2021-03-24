<?php


namespace core\task;


use pocketmine\scheduler\Task;

class DelayedClosureTask extends Task
{
    /**
     * @var \Closure
     */
    private $callback;

    /**
     * DelayedClosureTask constructor.
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function onRun(int $currentTick) : void {
        call_user_func($this->callback, $currentTick);
    }

}