<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use core\kit\Kit;
use pocketmine\nbt\tag\CompoundTag;

class ChestKit extends CustomItem {

    const KIT = "Kit";

    /**
     * ChestKit constructor.
     *
     * @param Kit $kit
     */
    public function __construct(Kit $kit) {
        $customName = "§kII§r §l§d{$kit->getName()} §r§7Kit§r §f§kII";
        $lore = [];
        $lore[] = "";
        $lore[] = "§r§7Tap anywhere to uncover {$kit->getName()}§r";
        $lore[] = "";
        $lore[] = "§eTap anywhere to uncover this kit!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::KIT, $kit->getName());
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}