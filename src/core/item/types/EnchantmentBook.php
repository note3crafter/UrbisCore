<?php

declare(strict_types = 1);

namespace core\item\types;

use core\item\CustomItem;
use core\item\ItemManager;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class EnchantmentBook extends CustomItem {

    const ENCHANTMENT = "Enchantment";

    /**
     * EnchantmentBook constructor.
     *
     * @param Enchantment $enchantment
     */
    public function __construct(Enchantment $enchantment) {
        $customName = "§l§d{$enchantment->getName()} Enchantment Book";
        $lore = [];
        $lore[] = "";
        $lore[] = "§7Drag this book to them item you wan't to put the enchant on!";
        $lore[] = "";
        $lore[] = "§l§cWARNING:§r §7Do not combine stacked enchantbooks to items it will just be 1!";
        $lore[] = "";
        $lore[] = "§dTo check this enchantment do §l/ceinfo§r §dfor information.";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setInt(self::ENCHANTMENT, $enchantment->getId());
        $tag->setString("UniqueId", uniqid());
        parent::__construct(self::ENCHANTED_BOOK, $customName, $lore);
    }

    public function getMaxStackSize(): int{
        return 64;
    }
}
