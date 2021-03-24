<?php

namespace core\gamble;

use core\Urbis;
use core\CorePlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class GambleListener implements Listener {

    /** @var Urbis */
    private $core;

    /**
     * GambleListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $this->core->getGambleManager()->createRecord($player);
    }

    /**
     * @priority NORMAL
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $this->core->getGambleManager()->removeCoinFlip($player);
    }
}