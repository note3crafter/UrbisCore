<?php

declare(strict_types = 1);

namespace core\envoy\event\task;

use core\envoy\EnvoyManager;
use core\faction\Faction;
use core\Urbis;
//use core\libs\utils\UtilsException;
use core\utils\UtilsException;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class EnvoyHeartbeatTask extends Task {

    public const ENVOY_PREFIX = "§8§l(§3!§8) §r§7";
    public const DELAY = 60 * 20;

    /** @var EnvoyManager */
    private $manager;

    /** @var Level */
    private $level;

    private $delay = 0;

    /**
     * EnvoyHeartbeatTask constructor.
     *
     * @param EnvoyManager $manager
     */
    public function __construct(EnvoyManager $manager) {
        $this->manager = $manager;
        $this->level = Server::getInstance()->getLevelByName("FactionsWorld");
    }

    /**
     * @param int $currentTick
     *
     * @throws UtilsException
     */
    public function onRun(int $currentTick) {
        if(Urbis::getInstance()->getAnnouncementManager()->getRestarter()->getRestartProgress() > 5) {
            if(count($this->manager->getEnvoys()) < 5) {
                if ($this->delay < 1){
                    $x = mt_rand(1, 1000);
                    $z = mt_rand(1, 1000);

                    //$this->level->loadChunk($x, $z, true);
                    $level = Server::getInstance()->getLevelByName(Faction::CLAIM_WORLD);
                    $y = $this->level->getHighestBlockAt($x, $z);
                    if($y < 0) {
                        return;
                    }
                    $position = $this->level->getSafeSpawn(new Vector3($x, $y, $z));
                    $this->manager->spawnEnvoy($position);
                    Server::getInstance()->broadcastMessage("\n\n§rAn §l§bENVOY§r has been spawned use §l§e/envoys§r to see list of envoys.\n\n");
                }
                $this->delay++;
                if ($this->delay > 60){
                    $this->delay = 0;
                }
            }
            foreach($this->manager->getEnvoys() as $envoy) {
                $envoy->tick();
            }
            return;
        }
        if(count($this->manager->getEnvoys()) > 0) {
            foreach($this->manager->getEnvoys() as $envoy) {
                $envoy->despawn();
            }
        }
    }
}
