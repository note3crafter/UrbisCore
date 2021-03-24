<?php

declare(strict_types = 1);

namespace core\announcement\task;

use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\command\ConsoleCommandSender;

class RestartTask extends Task {

    /** @var Urbis */
    private $core;

    /** @var int */
    private $time = 5400;

    /**
     * RestartTask constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
    }

    /**
     * @param int $currentTick
     *
     * @throws TranslationException
     */
    public function onRun(int $currentTick) {
        $hours = floor($this->time / 3600);
        $minutes = floor(($this->time / 60) % 60);
        $seconds = $this->time % 60;
        if($minutes % 10 == 0 and $seconds == 0) {
            $this->core->getServer()->broadcastMessage(Translation::getMessage("restartMessage", [
                "hours" => $hours,
                "minutes" => $minutes,
                "seconds" => $seconds
            ]));
        }
        if($hours < 1) {
            if($minutes == 0 and $seconds == 5) {
                foreach($this->core->getServer()->getOnlinePlayers() as $player) {
                    if(!$player instanceof CorePlayer) {
                        continue;
                    }
                    $player->removeAllWindows();
                }
            }
            if($minutes == 0 and $seconds == 0) {
                $this->core->getServer()->dispatchCommand(new ConsoleCommandSender(), 'save-all');
                foreach($this->core->getServer()->getOnlinePlayers() as $player) {
                    if(!$player instanceof CorePlayer) {
                        continue;
                    }
                    if($player->isTagged()) {
                        $player->combatTag(false);
                    }
                    $player->teleport(Urbis::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
                    $player->close("", "§r§b§lREBOOTING§r");
                }
                $this->core->getServer()->shutdown();
            }
        }
        $this->time--;
    }

    /**
     * @param int $time
     */
    public function setRestartProgress(int $time): void {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getRestartProgress(): int {
        return $this->time;
    }
}
