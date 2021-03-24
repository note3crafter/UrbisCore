<?php

declare(strict_types = 1);

namespace core\item\types\notes;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class BloodyNote extends CustomItem {

    const BLOODYNOTE = "BloodyNote";

    /**
     * BloodyNote constructor.
     */
    public function __construct() {
        $customName = "§l§cBloody Note";
        $lore = [];
        $lore[] = "§7Uncover this Bloody Note to gain amount of §l§7Money§r§r§7.";
        $lore[] = "";
        $lore[] = "§c§l*§r §7Note Worth: §c§kG824na§r";
        $lore[] = "";
        $lore[] = "§c§l(!)§r §cTap to uncover the Bloody Note!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::BLOODYNOTE, "BloodyNote");
        parent::__construct(self::PAPER, $customName, $lore);
    }
}