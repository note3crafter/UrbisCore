<?php

namespace core\combat\boss\tasks;

use core\combat\boss\Boss;
use core\combat\boss\types\{Alien, Thamuz, Argus};
use core\Urbis;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class SpawnRandomBoss extends Task
{

    /** @var string */
    protected $prefix = "§l§8(§3!§8)§r §7";
    /** @var int */
    protected $time = 1800; // 30 minutes.
    /** @var bool */
    protected $sentWarning = false;

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        if (!$this->sentWarning and $this->check()) {
            Server::getInstance()->broadcastMessage("§7The summoning boss has been §l§cPAUSED§r");
            $this->sentWarning = true;
            return;
        }
        if ($this->check()) {
            return;
        }
        if ($this->sentWarning) {
            $this->sentWarning = false;
        }
        if (($this->time % 300) == 0) {
            $time = ($this->time >= 60) ? floor(($this->time / 60) % 60) . " §7minutes§r" : $this->time . " §7seconds§r";
            Server::getInstance()->broadcastMessage("§7A §l§6BOSS§r §7will be summoned in " . $time . "§r");
        }
        if ($this->time <= 0) {
            if (!$this->check()) $this->summon();
            $this->time = 780;
        } else {
            --$this->time;
        }
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        $lvl = Server::getInstance()->getLevelByName(Urbis::getInstance()->bossData->get("arena")["level"]);
		if(!$lvl instanceof Level) Server::getInstance()->loadLevel(Urbis::getInstance()->bossData->get("arena")["level"]);

        foreach ($lvl->getEntities() as $entity) {
            if ($entity instanceof Alien or $entity instanceof Thamuz or $entity instanceof Argus) {
                return true;
                break;
            }
        }
        return false;
    }

    public function summon(): void
    {
    	switch (rand(0, 1)){
			case 0:
				$class = Urbis::getInstance()->getCombatManager()->getBossNameByIdentifier(1); // Thamudz
				break;
			case 1:
				$class = Urbis::getInstance()->getCombatManager()->getBossNameByIdentifier(3); // Argus
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
        Server::getInstance()->broadcastMessage("§7A §l§bBOSS§r §7has been summoned it may be:§r\n§c--------------------\n\n§l§6THAMUZ\n\n§7or\n\n§l§aARGUS§r\n§c--------------------\n§7Teleport to the boss arena with §l§b/boss§r §7to fight the boss!\n\n");
    }
}
