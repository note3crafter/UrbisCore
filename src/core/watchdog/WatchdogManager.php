<?php

declare(strict_types = 1);

namespace core\watchdog;

use core\Urbis;

class WatchdogManager {

    /** @var Urbis */
    private $core;

    /**
     * WatchdogManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new WatchdogListener($core), $core);
    }
}
