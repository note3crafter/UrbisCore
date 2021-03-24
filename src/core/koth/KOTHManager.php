<?php

namespace core\koth;

use core\Urbis;
use core\koth\task\KOTHHeartbeatTask;
use core\koth\task\KOTHStartGameTask;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;

class KOTHManager {

    /** @var Urbis */
    private $core;

    /** @var KOTHArena[] */
    private $arenas = [];

    /** @var null|KOTHArena */
    private $game = null;

    /**
     * KOTHManager constructor.
     *
     * @param Urbis $core
     *
     * @throws KOTHException
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->init();
        //$this->core->getScheduler()->scheduleRepeatingTask(new KOTHHeartbeatTask($this), 20);
        //$this->core->getScheduler()->scheduleDelayedTask(new KOTHStartGameTask($this), 20);
    }

    /**
     * @throws KOTHException
     */
    public function init(): void {
        //$this->arenas[] = new KOTHArena("Agara", new Position(-85, 14, 122, $this->core->getServer()->getLevelByName("koth")), new Position(-89, Level::Y_MAX, 118, $this->core->getServer()->getLevelByName("koth")), 250);
    }

    /**
     * @return KOTHArena[]
     */
    public function getArenas(): array {
        return $this->arenas;
    }

    /**
     * @throws TranslationException
     */
    public function startEndOfTheWorldKOTH(): void {
        $eotwArena = null;
        foreach($this->arenas as $arena) {
            if($arena->getName() === "AAA") {
                $eotwArena = $arena;
            }
        }
        if($eotwArena === null) {
            return;
        }
        $this->game = $eotwArena;
        //$this->core->getServer()->broadcastMessage($eotwArena->getName() . " KOTH has now began!!!");
    }

    /**
     * @throws TranslationException
     */
    public function startGame(): void {
        if(empty($this->arenas)) {
            return;
        }
        $arena = $this->arenas[array_rand($this->arenas)];
        $this->game = $arena;
        $this->core->getServer()->broadcastMessage($arena->getName() . " KOTH has now began!!!");
    }

    public function endGame(): void {
        $this->game = null;
        $this->core->getScheduler()->scheduleDelayedTask(new KOTHStartGameTask($this), 432000);
    }

    /**
     * @return KOTHArena|null
     */
    public function getGame(): ?KOTHArena {
        return $this->game;
    }
}