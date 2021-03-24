<?php

declare(strict_types = 1);

namespace core\item\types\relics;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class CommonRelic extends CustomItem {

    const COMMONRELIC = "CommonRelic";

    /**
     * CommonRelic constructor.
     */
    public function __construct() {
        $customName = "§l§aCommon §r§fRelic";
        $lore = [];
        $lore[] = "";
        $lore[] = "§7Tap anywhere to uncover this relic.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::COMMONRELIC, "CommonRelic");
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}