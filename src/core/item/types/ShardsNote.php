<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class ShardsNote extends CustomItem {

    const SHARDS = "Shards";

    /**
     * ShardsNote constructor.
     *
     * @param int $amount
     */
    public function __construct(int $amount) {
        $customName = "§r§l§3" . number_format($amount) . " §l§3Shards";
        $lore = [];
        $lore[] = "";
        $lore[] = "§r§7Tap anywhere to claim §l§3" . "$amount §l§3Shards§r§7.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setInt(self::SHARDS, $amount);
        parent::__construct(self::PAPER, $customName, $lore);
    }
}