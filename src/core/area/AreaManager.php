<?php

declare(strict_types = 1);

namespace core\area;

use core\Urbis;
use pocketmine\level\Position;

class AreaManager {

    /** @var Urbis */
    private $core;

    /** @var Area[] */
    private $areas = [];

    /**
     * AreaManager constructor.
     *
     * @param Urbis $core
     *
     * @throws AreaException
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new AreaListener($core), $core);
        $this->init();
    }

    /**
     * @throws AreaException
     */
    public function init(): void {
        $this->addArea(new Area("Spawn", new Position(-7000, 0, -7000, $this->core->getServer()->getDefaultLevel()), new Position(1000, 256, 1000, $this->core->getServer()->getDefaultLevel()), false, false));
        $this->addArea(new Area("Boss", new Position(286, 102, 215, $this->core->getServer()->getLevelByName("boss")), new Position(1000, 256, 1000, $this->core->getServer()->getLevelByName("boss")), true, false));
        $this->addArea(new Area("Koth", new Position(286, 102, 215, $this->core->getServer()->getLevelByName("koth")), new Position(1000, 256, 1000, $this->core->getServer()->getLevelByName("koth")), true, false));
    }

    /**
     * @param Area $area
     */
    public function addArea(Area $area): void {
        $this->areas[] = $area;
    }

    /**
     * @param Position $position
     *
     * @return Area[]|null
     */
    public function getAreasInPosition(Position $position): ?array {
        $areas = $this->getAreas();
        $areasInPosition = [];
        foreach($areas as $area) {
            if($area->isPositionInside($position) === true) {
                $areasInPosition[] = $area;
            }
        }
        if(empty($areasInPosition)) {
            return null;
        }
        return $areasInPosition;
    }

    /**
     * @return Area[]
     */
    public function getAreas(): array {
        return $this->areas;
    }
}