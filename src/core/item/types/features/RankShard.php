<?php

declare(strict_types = 1);

namespace core\item\types\features;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class RankShard extends CustomItem {

    const RANKSHARD = "RankShard";

    /**
     * RankShard constructor.
     */
    public function __construct() {
        $customName = "§3§lRank §bShard§r";
        $lore = [];
        $lore[] = "";
        $lore[] = "§7Obtain §bRandom §7ranks with this shard up to §4§lMiercenary §r§7Rank.";
        $lore[] = "";
        $lore[] = "§l§fType: §r§7Permanent";
        $lore[] = "";
        $lore[] = "§c§lWARNING: §r§7This can only be used §l§cONCE §r§7and not able to be refunded if lost.";
        $lore[] = "";
        $lore[] = "§eTap anywhere to uncover this shard and to redeem a random rank.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::RANKSHARD, "RankShard");
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::PRISMARINE_SHARD, $customName, $lore);
    }
}
