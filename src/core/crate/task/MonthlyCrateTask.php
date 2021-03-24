<?php

namespace core\crate\task;

use core\Urbis;
use core\UrbisPlayer;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\scheduler\Task;

class MonthlyCrateTask extends Task
{
    /** @var UrbisPlayer */
    private $player;
    /** @var Position */
    private $pos;
    /** @var int */
    private $time = 8;

    /**
     * MonthlyCrateTask constructor.
     * @param UrbisPlayer $player
     * @param Position $pos
     */
    public function __construct(UrbisPlayer $player, Position $pos)
    {
        $this->player = $player;
        $this->pos = $pos;
        $this->setHandler(Urbis::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20));
    }

    /**
     * @return UrbisPlayer
     */
    public function getPlayer(): UrbisPlayer
    {
        return $this->player;
    }

    /**
     * @return Position
     */
    public function getPos(): Position
    {
        return $this->pos;
    }

    public function onRun(int $currentTick)
    {
        $block = $this->getPos();
        $monthlyCrate = Urbis::getInstance()->getCrateManager()->getMonthlyCrate();
        $player = $this->getPlayer();
        if ($player instanceof UrbisPlayer) {
            $pk = new AddActorPacket();
            $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[EntityIds::LIGHTNING_BOLT];
            $pk->entityRuntimeId = Entity::$entityCount++;
            $pk->position = $block->add(0, 1);
            $pk->yaw = 0;
            $pk->pitch = 0;
            $player->sendDataPacket($pk);
            if (--$this->time <= 0) {
                $monthlyCrate->getReward()->getCallback()($player);
                $player->getLevelNonNull()->setBlock($block, Block::get(Block::AIR), true, true);
                Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            }
        } else {
            $block->getLevel()->dropItem($block->add(0, 1), $monthlyCrate->getReward()->getItem());
            $player->getLevelNonNull()->setBlock($block, Block::get(Block::AIR), true, true);
            Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}
