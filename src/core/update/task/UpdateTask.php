<?php

declare(strict_types = 1);

namespace core\update\task;

use core\Urbis;
use core\CorePlayer;
use libs\utils\UtilsException;
use pocketmine\scheduler\Task;

class UpdateTask extends Task {

    /** @var Urbis */
    private $core;

    /** @var CorePlayer[] */
    private $players = [];

    /**
     * UpdateTask constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->players = $core->getServer()->getOnlinePlayers();
    }

    /**
     * @param int $tick
     *
     * @throws UtilsException
     */
    public function onRun(int $tick) {
        if(empty($this->players)) {
            $this->players = $this->core->getServer()->getOnlinePlayers();
        }
        $player = array_shift($this->players);
        if(!$player instanceof CorePlayer) {
            return;
        }
        if($player->isOnline() === false) {
            return;
        }
        $this->core->getUpdateManager()->updateScoreboard($player);
    }
}