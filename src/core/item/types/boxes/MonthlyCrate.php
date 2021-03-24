<?php

namespace core\item\types\boxes;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use \core\crate\types\MonthlyCrate as Crate;

class MonthlyCrate extends CustomItem {

    const MONTHLY_CRATE = "Monthly Crate";

    /**
     * MonthlyCrate constructor.
     */
    public function __construct() {
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::MONTHLY_CRATE, self::MONTHLY_CRATE);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::CHEST, Crate::PREFIX, []);
    }
}