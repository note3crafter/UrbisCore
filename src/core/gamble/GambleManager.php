<?php

namespace core\gamble;

use core\Urbis;
use core\CorePlayer;

class GambleManager {

    /** @var Urbis */
    private $core;

    /** @var int[] */
    private $coinFlips = [];

    /** @var string[] */
    private $coinFlipRecord = [];

    /**
     * GambleManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->core->getServer()->getPluginManager()->registerEvents(new GambleListener($core), $core);
    }

    /**
     * @return int[]
     */
    public function getCoinFlips(): array {
        return $this->coinFlips;
    }

    /**
     * @param CorePlayer $player
     *
     * @return int|null
     */
    public function getCoinFlip(CorePlayer $player): ?int {
        return $this->coinFlips[$player->getName()] ?? null;
    }

    /**
     * @param CorePlayer $player
     * @param int           $amount
     */
    public function addCoinFlip(CorePlayer $player, int $amount): void {
        if(isset($this->coinFlips[$player->getName()])) {
            return;
        }
        $this->coinFlips[$player->getName()] = $amount;
    }

    /**
     * @param CorePlayer $player
     */
    public function removeCoinFlip(CorePlayer $player): void {
        if(!isset($this->coinFlips[$player->getName()])) {
            return;
        }
        unset($this->coinFlips[$player->getName()]);
    }

    /**
     * @param CorePlayer $player
     * @param $wins
     * @param $losses
     */
    public function getRecord(CorePlayer $player, &$wins, &$losses): void {
        $record = $this->coinFlipRecord[$player->getName()];
        $reward = explode(":", $record);
        $wins = $reward[0];
        $losses = $reward[1];
    }

    /**
     * @param CorePlayer $player
     */
    public function createRecord(CorePlayer $player): void {
        $this->coinFlipRecord[$player->getName()] = "0:0";
    }

    /**
     * @param CorePlayer $player
     */
    public function addWin(CorePlayer $player): void {
        if(!isset($this->coinFlipRecord[$player->getName()])){
            $this->coinFlipRecord[$player->getName()] = "0:0";
        }
        $record = $this->coinFlipRecord[$player->getName()];
        $reward = explode(":", $record);
        $wins = intval($reward[0]) + 1;
        $losses = $reward[1];
        $this->coinFlipRecord[$player->getName()] = "$wins:$losses";
    }

    /**
     * @param CorePlayer $player
     */
    public function addLoss(CorePlayer $player): void {
        $record = $this->coinFlipRecord[$player->getName()];
        $reward = explode(":", $record);
        $wins = $reward[0];
        $losses = $reward[1] + 1;
        $this->coinFlipRecord[$player->getName()] = "$wins:$losses";
    }
}