<?php

declare(strict_types = 1);

namespace core\item\types\moneypouches;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class CommonPouch extends CustomItem {

    const COMMONPOUCH = "CommonPouch";

    /**
     * CommonPouch constructor.
     */
    public function __construct() {
        $customName = "§l§eMoney Pouch§r §7(Right Click)§r";
        $lore = [];
        $lore[] = "§7Recieve a random amount of balance with this pouch.";
        $lore[] = "";
        $lore[] = "§ePouch Information:";
        $lore[] = "§7Tier:§r §aCommon";
        $lore[] = "§7Pouch Worth:§r §e§k486529";
        $lore[] = "§r§e(!) Tap anywhere to uncover this Money Pouch.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::COMMONPOUCH, "CommonPouch");
        parent::__construct(self::ENDER_CHEST, $customName, $lore);
    }
}