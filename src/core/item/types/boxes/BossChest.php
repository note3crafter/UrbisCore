<?php

declare(strict_types = 1);

namespace core\item\types\boxes;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class BossChest extends CustomItem {

    const BOSSCHEST = "BossChest";

    /**
     * BossChest constructor.
     */
    public function __construct() {
        $customName = TextFormat::RESET . TextFormat::RED . TextFormat::BOLD . "§6§lBoss Chest§r";
        $lore = [];
        $lore[] = "§7Obtain by killing §l§aArgus §r§7boss";
        $lore[] = "";
        $lore[] = "";
        $lore[] = "§l§4NOTE: §r§7You can only get one reward on this rewards!";
        $lore[] = "";
        $lore[] = "§l§6REWARDS§r";
        $lore[] = "§7- Mask Charm";
        $lore[] = "§7- Legendary Crate Key Note";
        $lore[] = "§7- Agara Hero Bone Summon";
        $lore[] = "§7- $150,000";
        $lore[] = "§7- Common Crate Key Note";
        $lore[] = "";
        $lore[] = "§eTap to uncover this Chest and recieve rewards!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::BOSSCHEST, "BossChest");
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::CHEST, $customName, $lore);
    }
}
