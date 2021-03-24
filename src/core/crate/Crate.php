<?php

declare(strict_types = 1);

namespace core\crate;

use core\crate\task\AnimationTask;
use core\Urbis;
use core\CorePlayer;
use core\libs\form\CustomForm;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\level\Position;

abstract class Crate {

    const COMMON = "Common";

    const LEGENDARY = "Legendary";

    const EPIC = "Epic";
 
    const RARE = "Rare";

    const VOTE = "Vote";

    const MYTHIC = "Mythic";

    /** @var string */
    private $name;

    /** @var Position */
    private $position;

    /** @var Reward[] */
    private $rewards = [];

    /**
     * Crate constructor.
     *
     * @param string $name
     * @param Position $position
     * @param Reward[] $rewards
     */
    public function __construct(string $name, Position $position, array $rewards) {
        $this->name = $name;
        $this->position = $position;
        $this->rewards = $rewards;
    }

    /**
     * @param CorePlayer $player
     */
    abstract public function spawnTo(CorePlayer $player): void;

    /**
     * @param CorePlayer $player
     */
    abstract public function updateTo(CorePlayer $player): void;

    /**
     * @param CorePlayer $player
     */
    abstract public function despawnTo(CorePlayer $player): void;

    /**
     * @param Reward        $reward
     * @param CorePlayer $player
     */
    abstract public function showReward(Reward $reward, CorePlayer $player): void;

    /**
     * @param CorePlayer $player
     *
     * @param int|null $count
     * @throws TranslationException
     */
    public function try(CorePlayer $player, int $count = null): void {
        if($count < 0) return; // Safety reasons.

        $keys = $player->getKeys($this);
        $emptySlots = $player->getInventory()->getSize() - count($player->getInventory()->getContents());

        if($player->isRunningCrateAnimation() === true) {
            $player->sendMessage(Translation::getMessage("animationAlreadyRunning"));
            $this->pushBack($player);
            return;
        }
        if($emptySlots <= 0 || $emptySlots < ($count ?? 1)) {
            $player->sendMessage(Translation::getMessage("fullInventory"));
            $this->pushBack($player);
            return;
        }
        if($keys <= 0 || $keys < ($count ?? 1)) {
            $player->sendMessage(Translation::getMessage("noKeys"));
            $this->pushBack($player);
            return;
        }
        if($keys === 1) $count = 1;

        if($count === null && $keys > 1) {
            $player->sendForm(new CrateForm($this, $player));
            return;
        }
        $player->removeKeys($this, $count);
        Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new AnimationTask($this, $player, $count), 10);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position {
        return $this->position;
    }

    /**
     * @return Reward[]
     */
    public function getRewards(): array {
        return $this->rewards;
    }

    public function pushBack(CorePlayer $player) : void {
        $player->knockBack($player, 0, $player->getX() - $this->position->getX(), $player->getZ() - $this->position->getZ(), 1);
    }

    /**
     * @param int $loop
     *
     * @return Reward
     */
    public function getReward(int $loop = 0): Reward {
        $chance = mt_rand(0, 100);
        $reward = $this->rewards[array_rand($this->rewards)];
        if($loop >= 10) {
            return $reward;
        }
        if($reward->getChance() <= $chance) {
            return $this->getReward($loop + 1);
        }
        return $reward;
    }

}