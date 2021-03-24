<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use core\item\ItemManager;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class Artifact extends CustomItem {

    const ARTIFACT = "Artifact";

    /**
     * Artifact constructor.
     */
    public function __construct() {
        $customName = TextFormat::RESET . TextFormat::RED . TextFormat::BOLD . "§b§lArti§ffact§r";
        $lore = [];
        $lore[] = "";
        $lore[] = TextFormat::RESET . TextFormat::YELLOW . "This Artifact contains powerful kits to use against others";
        $lore[] = TextFormat::RESET . TextFormat::YELLOW . "Tap anywhere to use and obtain a Meta Box";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::ARTIFACT, self::ARTIFACT);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::NETHER_QUARTZ, $customName, $lore);
    }

    public function getMaxStackSize(): int{
        return 64;
    }
}
