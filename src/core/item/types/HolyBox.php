<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use core\kit\Kit;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class HolyBox extends CustomItem {

    const SACRED_KIT = "SacredKit";

    /**
     * HolyBox constructor.
     *
     * @param Kit $kit
     */
    public function __construct(Kit $kit) {
        $customName = "§l§e{$kit->getName()} §r§l§eMeta Box§r";
        $lore = [];
        $lore[] = "";
        $lore[] = TextFormat::RESET . TextFormat::GRAY . "Place in spawn to open this box for a chance to get a godly kit permanently!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::SACRED_KIT, $kit->getName());
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}