<?php

declare(strict_types=1);

namespace core\item\types;

use core\item\CustomItem;
use core\UrbisPlayer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class Cannon extends CustomItem{

    const PLAYER = "Player";
    public const CANON_META = 3;

    /**
     * Head constructor.
     *
     * @param UrbisPlayer $player
     */
    public function __construct(){
        $customName = TextFormat::BOLD . TextFormat::LIGHT_PURPLE . "Cannon";
        $lore = [];
        $lore[] = "";
        $lore[] = TextFormat::RED . "Place down to summon a Portable Cannon of the medieval war!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        parent::__construct(self::HORSE_ARMOR_DIAMOND, $customName, $lore, [], [], 3);
    }
}
