<?php

declare(strict_types = 1);

namespace core\item\types\features;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class FlyEffect extends CustomItem {

    const FLYEFFECT = "FlyEffect";

    /**
     * FlyEffect constructor.
     */
    public function __construct() {
        $customName = TextFormat::RESET . TextFormat::RED . TextFormat::BOLD . "§e§lFly§r §7(Right Click)";
        $lore = [];
        $lore[] = "";
        $lore[] = "§bPermanently Obtain Fly permission to be able to fly.";
        $lore[] = "7Tap to uncover this permission.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::FLYEFFECT, self::FLYEFFECT);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::FEATHER, $customName, $lore);
    }
}
