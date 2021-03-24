<?php

declare(strict_types = 1);

namespace core\item\types\mobs;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class ZephyrBone extends CustomItem {

    const ZEPHYRBONE = "Zephyr";

    /**
     * RareRelic constructor.
     */
    public function __construct() {
        $customName = "§l§cZephyr §r§fBone";
        $lore = [];
        $lore[] = "";
        $lore[] = "§7Tap anywhere to summon this powerful fallen hero zephyr!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::ZEPHYRBONE, "ZephyrBone");
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}