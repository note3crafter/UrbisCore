<?php


namespace core\entity;

use core\CorePlayer;
use pocketmine\block\Block;
use pocketmine\block\Flowable;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\Player;

abstract class EntityAI extends Creature
{
    const FIND_DISTANCE = 20;

    const LOSE_DISTANCE = 25;

    const ATTACK_DISTANCE = 5;

    /** @var int */
    public $attackDamage;

    /** @var float */
    public $speed;

    /** @var int */
    public $attackWait;

    /** @var int */
    public $regenerationWait = 0;

    /** @var int */
    public $regenerationRate;

    /** @var float[] */
    protected $damages = [];

    /** @var Player|null */
    private $target = null;

    /** @var int */
    private $findNewTargetTicks = 0;

    /** @var int */
    private $jumpTicks = 5;


    public function onUpdate(int $currentTick): bool
    {
        if ($this->isAlive()) {

            $this->findNewTarget();
            $player = $this->getTarget();

            if ($player instanceof CorePlayer && $player->isAlive() && !$player->isClosed()) {
                if (!$this->isOnGround()) {
                    if ($this->motion->y > -$this->gravity * 4) {
                        $this->motion->y = -$this->gravity * 4;
                    } else {
                        $this->motion->y += $this->isUnderwater() ? $this->gravity : -$this->gravity;
                    }
                } else {
                    $this->motion->y -= $this->gravity;
                }
                if ($this->shouldJump()) $this->jump();
                $x = $player->x - $this->x;
                $y = $player->y - $this->y;
                $z = $player->z - $this->z;
                $this->yaw = rad2deg(atan2(-$x, $z));
                $this->pitch = rad2deg(-atan2($y, sqrt($x * $x + $z * $z)));
                if ($x ** 2 + $z ** 2 < 0.5) {
                    $x = 0;
                    $z = 0;
                } else {
                    $diff = abs($x) + abs($z);
                    $x = $this->speed * 0.15 * ($x / $diff);
                    $z = $this->speed * 0.15 * ($z / $diff);
                }
                if ($this->distance($player) < self::ATTACK_DISTANCE && --$this->attackWait <= 0) {
                    if ($player->isCreative() or $player->isSpectator()) {
                        $this->target = null;
                        return !$this->closed;
                    }
                    $player->doHitAnimation();
                    $this->broadcastEntityEvent(ActorEventPacket::ARM_SWING);
                    $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->attackDamage);
                    $player->attack($ev);
                    $this->attackWait = 20;
                }
                $this->move($x, $y, $z);
                $this->updateMovement();
            }
        }
        parent::onUpdate($currentTick);
        return !$this->closed;
    }

    /**
     * @param EntityDamageEvent $source
     */
    public function attack(EntityDamageEvent $source): void
    {
        if ($source instanceof EntityDamageByEntityEvent) {
            $killer = $source->getDamager();
            if ($killer instanceof Player) {
                if ($killer->isFlying() or $killer->getAllowFlight() == true) {
                    $killer->setFlying(false);
                    $killer->setAllowFlight(false);
                }
                if ($this->target === null or $this->target->getName() != $killer->getName() and !$killer->isCreative()) {
                    $this->target = $killer;
                }
                if (isset($this->damages[$killer->getName()])) {
                    $this->damages[$killer->getName()] += $source->getFinalDamage();
                } else {
                    $this->damages[$killer->getName()] = $source->getFinalDamage();
                }
            }
        }
        parent::attack($source);
    }

    /**
     * @param Entity $attacker
     * @param float $damage
     * @param float $x
     * @param float $z
     * @param float $base
     */
    public function knockBack(Entity $attacker, float $damage, float $x, float $z, float $base = 0.4): void
    {
        parent::knockBack($attacker, $damage, $x, $z, $base * 2);
    }

    public function findNewTarget()
    {
        $distance = self::FIND_DISTANCE;
        $target = null;
        foreach ($this->getLevel()->getPlayers() as $player) {
            if ($player instanceof CorePlayer and $player->distance($this) <= $distance and (!$player->isCreative())) {
                $distance = $player->distance($this);
                $target = $player;
            }
        }
        $this->findNewTargetTicks = 60;
        $this->target = ($target != null ? $target : null);
    }

    /**
     * @return bool
     */
    public function hasTarget(): bool
    {
        $target = $this->getTarget();
        if ($target == null) {
            return false;
        }
        return true;
    }

    /**
     * @return Player|null
     */
    public function getTarget(): ?Player
    {
        return $this->target;
    }

    /**
     * @return float
     */
    public function getSpeed(): float
    {
        return ($this->isUnderwater() ? $this->speed / 2 : $this->speed);
    }

    /**
     * @return int
     */
    public function getBaseAttackDamage(): int
    {
        return $this->attackDamage;
    }

    /**
     * @param float $y
     *
     * @return Block
     */
    public function getFrontBlock($y = 0.0): Block
    {
        $dv = $this->getDirectionVector();
        $pos = $this->asVector3()->add($dv->x * $this->getScale(), $y + 1, $dv->z * $this->getScale())->round();
        return $this->getLevel()->getBlock($pos);
    }

    /**
     * @return bool
     */
    public function shouldJump(): bool
    {
        if ($this->jumpTicks > 0) {
            return false;
        }
        return $this->isCollidedHorizontally or
            ($this->getFrontBlock()->getId() != 0 or $this->getFrontBlock(-1) instanceof Stair) or
            ($this->getLevel()->getBlock($this->asVector3()->add(0, -0, 5)) instanceof Slab and
                (!$this->getFrontBlock(-0.5) instanceof Slab and $this->getFrontBlock(-0.5)->getId() != 0)) and
            $this->getFrontBlock(1)->getId() === 0 and
            $this->getFrontBlock(2)->getId() === 0 and
            !$this->getFrontBlock() instanceof Flowable and
            $this->jumpTicks == 0;
    }

    /**
     * @return int
     */
    public function getJumpMultiplier(): int
    {
        if ($this->getFrontBlock() instanceof Slab or $this->getFrontBlock() instanceof Stair or
            $this->getLevel()->getBlock($this->asVector3()->subtract(0, 0.5)->round()) instanceof Slab and
            $this->getFrontBlock()->getId() != 0) {
            $fb = $this->getFrontBlock();
            if ($fb instanceof Slab and $fb->getDamage() > 0) {
                return 8;
            }
            if ($fb instanceof Stair and $fb->getDamage() > 0) {
                return 8;
            }
            return 4;
        }
        return 16;
    }

    public function jump(): void
    {
        $this->motion->y = $this->gravity * $this->getJumpMultiplier();
        $this->move($this->motion->x * 1.25, $this->motion->y, $this->motion->z * 1.25);
        $this->jumpTicks = 5;
    }

    protected function sendSpawnPacket(Player $player): void
    {
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[static::NETWORK_ID];
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->headYaw = $this->yaw; //TODO
        $pk->pitch = $this->pitch;
        $pk->attributes = $this->attributeMap->getAll();
        $pk->metadata = $this->propertyManager->getAll();

        $player->dataPacket($pk);
    }

}