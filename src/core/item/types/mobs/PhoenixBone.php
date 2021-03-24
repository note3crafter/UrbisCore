<?php


namespace core\item\types\mobs;

use core\item\CustomItem;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class PhoenixBone extends CustomItem
{
    const PHOENIX_BONE = "Agara";

    /**
     * PhoenixBone constructor.
     */
    public function __construct() {
        $customName = "§l§dAgara §r§fFallen Bone";
        $lore = ["",
            TextFormat::AQUA . "A Fallen Hero and Faithful §l§dAgara§r" ,
            TextFormat::AQUA . "§bSummons a Hero who strives to avenge its fallen brother",
            TextFormat::RESET . " ",
            TextFormat::WHITE . "Damage: " . TextFormat::GRAY . "11",
            TextFormat::WHITE . "Health: " . TextFormat::GRAY . "800"];
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setString(self::PHOENIX_BONE, self::PHOENIX_BONE);
        $tag->setString("UniqueId", uniqid());
        parent::__construct(Item::BONE, $customName, $lore);
    }
}
