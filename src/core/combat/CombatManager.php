<?php

declare(strict_types = 1);

namespace core\combat;

use core\combat\boss\Boss;
use core\combat\boss\BossException;
use core\combat\boss\types\Alien;
use core\combat\boss\types\Thamuz;
use core\combat\boss\types\Argus;
use core\combat\boss\heroes\Zephyr;
use core\Urbis;
use core\CorePlayer;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;

class CombatManager {

    /** @var Urbis */
    private $core;

    /** @var CombatListener */
    private $listener;

    /** @var string[] */
    private $bosses = [];

    /** @var Boss[] */
    private $spawned = [];

    /**
     * CombatManager constructor.
     * @param Urbis $core
     * @throws BossException
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->listener = new CombatListener($core);
        $core->getServer()->getPluginManager()->registerEvents($this->listener, $core);
        $this->init();
    }

    /**
     * @throws BossException
     */
    public function init(): void {
        $this->addBoss(Thamuz::class);
        $this->addBoss(Alien::class);
        $this->addBoss(Argus::class);
        $this->addBoss(Zephyr::class);
    }

    /**
     * @param CorePlayer $player
     *
     * @return int
     */
    public function getGodAppleCooldown(CorePlayer $player): int {
        $cd = 0;
        if(isset($this->listener->godAppleCooldown[$player->getRawUniqueId()])) {
            if((40 - (time() - $this->listener->godAppleCooldown[$player->getRawUniqueId()])) > 0) {
                $cd = 40 - (time() - $this->listener->godAppleCooldown[$player->getRawUniqueId()]);
            }
        }
        return $cd;
    }

    /**
     * @param CorePlayer $player
     *
     * @return int
     */
    public function getGoldenAppleCooldown(CorePlayer $player): int {
        $cd = 0;
        if(isset($this->listener->goldenAppleCooldown[$player->getRawUniqueId()])) {
            if((2 - (time() - $this->listener->goldenAppleCooldown[$player->getRawUniqueId()])) > 0) {
                $cd = 2 - (time() - $this->listener->goldenAppleCooldown[$player->getRawUniqueId()]);
            }
        }
        return $cd;
    }

    /**
     * @param CorePlayer $player
     *
     * @return int
     */
    public function getEnderPearlCooldown(CorePlayer $player): int {
        $cd = 0;
        if(isset($this->listener->enderPearlCooldown[$player->getRawUniqueId()])) {
            if((10 - (time() - $this->listener->enderPearlCooldown[$player->getRawUniqueId()])) > 0) {
                $cd = 10 - (time() - $this->listener->enderPearlCooldown[$player->getRawUniqueId()]);
            }
        }
        return $cd;
    }

    /**
     * @param string $bossClass
     *
     * @throws BossException
     */
    public function addBoss(string $bossClass) {
        Entity::registerEntity($bossClass);
        if(isset($this->bosses[constant("$bossClass::BOSS_ID")])) {
            throw new BossException("Unable to register boss due to duplicated boss identifier!");
        }
        $this->bosses[constant("$bossClass::BOSS_ID")] = $bossClass;
    }

    /**
     * @param int $identifier
     *
     * @return null|string
     */
    public function getBossNameByIdentifier(int $identifier): ?string {
        return $this->bosses[$identifier] ?? null;
    }

    /**
     * @param string $name
     *
     * @return int|null
     */
    public function getIdentifierByName(string $name): ?int {
        return array_search($name, $this->bosses) ?? null;
    }

    /**
     * @param int $bossId
     * @param Level $level
     * @param CompoundTag $tag
     */
    public function createBoss(int $bossId, Level $level, CompoundTag $tag) {
        $class = $this->getBossNameByIdentifier($bossId);
        /** @var Boss $entity */
        $entity = new $class($level, $tag);
        $entity->spawnToAll();
        $this->spawned{$entity->getId()} = $entity;
    }

	public function summonRandBoss(): void
	{
		switch (rand(0, 1)){
			case 0:
				$class = Urbis::getInstance()->getCombatManager()->getBossNameByIdentifier(1); //Meta
				break;
			case 1:
				$class = Urbis::getInstance()->getCombatManager()->getBossNameByIdentifier(3); //Argus
				break;
		}
		$position = Urbis::getInstance()->bossData->get("arena");
		$lvl = Server::getInstance()->getLevelByName($position["level"]);
		$pos = new Vector3($position["x"], $position["y"], $position["z"]);
		$lvl->loadChunk($pos->x >> 4, $pos->z >> 4, true);
		$nbt = Entity::createBaseNBT($pos);
		/** @var Boss $entity */
		$entity = new $class($lvl, $nbt);
		$entity->spawnToAll();
		Server::getInstance()->broadcastMessage("§fThe §l{$entity->getBossName()}§r§f has been summoned! the most dealt damage gets more rewards anyone else gets 1 do §l§d/boss §r§f or use §l§d/warp §r§f to get to the boss!");
	}
}
