<?php

declare(strict_types = 1);

namespace core\item\types\features;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class NitroGem extends CustomItem {

    const NITROGEM = "NitroGem";

    /**
     * Artifact constructor.
     */
    public function __construct() {
        $customName = "§d§lNitro §dGem§r";
        $lore = [];
        $lore[] = "";
        $lore[] = "§bYou can obtain various rewards with this gem for boosting our discord server.";
        $lore[] = "§eTap to uncover this Gem and recieve rewards!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::NITROGEM, self::NITROGEM);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::DIAMOND, $customName, $lore);
    }
}
