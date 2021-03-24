<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace core\entity\types;

use core\Urbis;
use core\entity\task\DespawnTask;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Fallable;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityBlockChangeEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\level\Position;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use function abs;
use function get_class;

class FallingBlock extends \pocketmine\entity\object\FallingBlock {
    public const NETWORK_ID = self::FALLING_BLOCK;

    public $width = 0.98;
    public $height = 0.98;

    protected $baseOffset = 0.49;

    protected $gravity = 0.04;
    protected $drag = 0.02;

    /** @var Block */
    protected $block;

    public $canCollide = false;



    public function entityBaseTick(int $tickDiff = 1) : bool{
        if($this->closed){
            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);

        if(!$this->isFlaggedForDespawn()){
            $pos = Position::fromObject($this->add(-$this->width / 2, $this->height, -$this->width / 2)->floor(), $this->getLevelNonNull());

            $this->block->position($pos);

            $blockTarget = null;
            if($this->block instanceof Fallable){
                $blockTarget = $this->block->tickFalling();
            }

            if($this->onGround or $blockTarget !== null){
                $x = $this->getX();
                $y = $this->getY();
                $z = $this->getZ();
                if (is_float($x)){
                    $x = ceil($x);
                }
                if (is_float($y)){
                    $y = ceil($y);
                }
                if (is_float($z)){
                    $z = ceil($z);
                }
                $blockatid = $this->level->getBlockAt($x, $y, $z)->getId();
                var_dump($y);
                Server::getInstance()->broadcastMessage(TextFormat::YELLOW . "$blockatid");
                if ($blockatid === 30){
                    $task = new DespawnTask($this);
                    Urbis::getInstance()->getScheduler()->scheduleDelayedTask($task, 60);
                }else{
                    $this->flagForDespawn();
                    $block = $this->level->getBlock($pos);
                    if(!$block->canBeReplaced() or ($this->onGround and abs($this->y - $this->getFloorY()) > 0.001)){
                        //FIXME: anvils are supposed to destroy torches
                        $this->getLevelNonNull()->dropItem($this, ItemFactory::get($this->getBlock(), $this->getDamage()));
                    }else{
                        $ev = new EntityBlockChangeEvent($this, $block, $blockTarget ?? $this->block);
                        $ev->call();
                        if(!$ev->isCancelled()){
                            $this->getLevelNonNull()->setBlock($pos, $ev->getTo(), true);
                        }
                    }
                    $hasUpdate = true;
                }
            }
        }

        return $hasUpdate;
    }

    public function getBlock() : int{
        return $this->block->getId();
    }

    public function getDamage() : int{
        return $this->block->getDamage();
    }

    public function saveNBT() : void{
        parent::saveNBT();
        $this->namedtag->setInt("TileID", $this->block->getId(), true);
        $this->namedtag->setByte("Data", $this->block->getDamage());
    }
}
