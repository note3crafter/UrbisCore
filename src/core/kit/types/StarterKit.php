<?php

declare(strict_types = 1);

namespace core\kit\types;

use core\item\CustomItem;
use core\kit\Kit;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class StarterKit extends Kit {

    /**
     * Starter constructor.
     */
    public function __construct() {
        $name = "§r§l§6Starter§r ";
        $items =  [
            (new CustomItem(Item::DIAMOND_HELMET, $name . " §r§7Helmet§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_CHESTPLATE, $name . " §r§7Chestplate§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_LEGGINGS, $name . " §r§7Leggings§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_BOOTS, $name . " §r§7Boots§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SWORD, $name . " §r§7Sword§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 2),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2),
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SHOVEL, " §r§7Shovel§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_PICKAXE, " §r§7Pickaxe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_AXE, " §r§7Axe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 2),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_HOE, $name . " §r§7Hoe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2)
            ]))->getItemForm(),
            Item::get(Item::STEAK, 0, 64),
            Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 3),
            Item::get(Item::OBSIDIAN, 0, 16),
            Item::get(Item::BEDROCK, 0, 8),
            Item::get(Item::DIAMOND, 0, 8),
            Item::get(Item::GOLD_INGOT, 0, 16),
            Item::get(Item::IRON_INGOT, 0, 16)
        ];
        parent::__construct("Starter", self::COMMON, $items, 1800);
    }
}