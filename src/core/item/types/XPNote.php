<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class XPNote extends CustomItem {

    const XP = "XP";

    /**
     * XPNote constructor.
     *
     * @param int $amount
     */
    public function __construct(int $amount) {
        $customName = "§dXP Note§r§f";
        $lore = [];
        $lore[] = "";
        $lore[] = "§bContains: §f$amount";
        $lore[] = "§fTap anywhere to claim the §dXP.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setInt(self::XP, $amount);
        parent::__construct(self::PAPER, $customName, $lore);
    }
}
