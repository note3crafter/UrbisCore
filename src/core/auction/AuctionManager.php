<?php
namespace core\auction;

use core\Urbis;

class AuctionManager
{

    /**
     * @var Urbis
     */
    private $core;

    public function __construct(Urbis $core)
    {
        $this->core = $core;
    }

}