<?php

declare(strict_types = 1);

namespace core\item\types\boxes;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class LootBag extends CustomItem {

    const LOOTBAG = "Lootbag";

    /**
     * LootBag constructor.
     */
    public function __construct() {
        $customName = "§l§fL§cO§4O§bT§eB§dA§aG§r";
        $lore = [];
        $lore[] = "";
        $lore[] = "§eTap anywhere to uncover and recieve rewards.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::LOOTBAG, "Lootbag");
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}