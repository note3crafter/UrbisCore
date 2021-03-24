<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use core\item\ItemManager;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class CobbleWallGenerator extends CustomItem {

    const COBBLEWALLGEN = "CobbleWallGenerator";

    /**
     * CobbleWallGenerator constructor.
     */
    public function __construct() {
        $customName = "§b§lCobblestone§r Wall Generator§r";
        $lore = [];
        $lore[] = "";
        $lore[] = "§7use to setblock";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::COBBLEWALLGEN, self::COBBLEWALLGEN);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::BUCKET, $customName, $lore);
    }

    public function getMaxStackSize(): int{
        return 64;
    }
}
