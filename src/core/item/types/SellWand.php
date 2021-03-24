<?php

namespace core\item\types;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class SellWand extends CustomItem {

    const USES = "Uses";

    /**
     * SellWand constructor.
     *
     * @param int $uses
     */
    public function __construct(int $uses) {
        $customName = "§cSell Wand";
        $lore = [];
        $lore[] = "";
        $lore = ["§cUses: §f$uses"];
        $lore = [];
        $lore = ["§cTap any chest to sell any contents of it."];
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setInt(self::USES, $uses);
        parent::__construct(self::DIAMOND_HOE, $customName, $lore);
    }
}