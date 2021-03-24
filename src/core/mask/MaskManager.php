<?php

declare(strict_types = 1);

namespace core\mask;

use core\mask\masks\ThanosMask;
use core\mask\masks\OutpostMask;
use core\mask\masks\CreeperMask;
use core\mask\masks\DragonMask;
use core\mask\masks\ArgusMask;
use core\mask\masks\GodMask;
use core\mask\masks\MaskAPI;
use core\mask\masks\MinerMask;
use core\mask\masks\AgaraMask;
use core\mask\masks\SkeletonMask;
use core\mask\masks\WitchMask;
use core\mask\masks\WitherMask;
use core\mask\masks\ZombieMask;
use core\mask\task\MaskTask;
use core\Urbis;
use pocketmine\Server;

class MaskManager{

    /** @var MaskAPI[] */
    private $masks = [];

    public function __construct(){
        Server::getInstance()->getPluginManager()->registerEvents(new MaskListener(), Urbis::getInstance());
        Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new MaskTask($this), 60);
        $this->register(new ThanosMask());
        $this->register(new OutpostMask());
        $this->register(new CreeperMask());
        $this->register(new DragonMask());
        $this->register(new ArgusMask());
        $this->register(new GodMask());
        $this->register(new MinerMask());
        $this->register(new AgaraMask());
        $this->register(new SkeletonMask());
        $this->register(new WitchMask());
        $this->register(new WitherMask());
        $this->register(new ZombieMask());
    }

    /**
     * @param MaskAPI $mask
     */
    public function register(MaskAPI $mask): void{
        $this->masks[$mask->getDamage()] = $mask;
    }

    /**
     * @return MaskAPI[]
     */
    public function getMasks(): array{
        return $this->masks;
    }
}