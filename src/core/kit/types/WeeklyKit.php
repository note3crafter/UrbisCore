<?php

declare(strict_types = 1);

namespace core\kit\types;

use core\item\CustomItem;
use core\kit\Kit;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class WeeklyKit extends Kit {

    /**
     * Weekly constructor.
     */
    public function __construct() {
        $name = "§r§l§dWeekly§r ";
        $items =  [
            (new CustomItem(Item::DIAMOND_HELMET, $name . "§r§7Helmet§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 6),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_CHESTPLATE, $name . "§r§7Chestplate§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 6),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_LEGGINGS, $name . "§r§7§r§7Leggings§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 6),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_BOOTS, $name . "§r§7Boots§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 6),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SWORD, $name . "§r§7Sword§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 9),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3),
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SHOVEL, $name . "§r§7Shovel§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_PICKAXE, $name . "§r§7Pickaxe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_AXE, $name . "§r§7Axe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 4),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_HOE, $name . "§r§7Hoe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3)
            ]))->getItemForm(),
            Item::get(Item::STEAK, 0, 64),
            Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 8),
            Item::get(Item::GOLDEN_APPLE, 0, 32),
            Item::get(Item::WOOD, 0, 64),
            Item::get(Item::TORCH, 0, 64),
            Item::get(Item::OBSIDIAN, 0, 128),
            Item::get(Item::BEDROCK, 0, 128)
        ];
        parent::__construct("Weekly", self::COMMON, $items, 604800);
    }
}