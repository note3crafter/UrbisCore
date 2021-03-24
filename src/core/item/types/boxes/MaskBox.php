<?php

declare(strict_types = 1);

namespace core\item\types\boxes;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class MaskBox extends CustomItem {

    const MASKBOX = "MaskCharmBox";

    /**
     * MaskBox constructor.
     */
    public function __construct() {
        $customName = "§l§aMask Charm §r§fBox";
        $lore = [];
        $lore[] = "";
        $lore[] = "§eTap anywhere to uncover and recieve Amount of Mask Charms.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::MASKBOX, "MaskCharmBox");
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}