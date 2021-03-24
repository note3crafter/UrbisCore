<?php

declare(strict_types = 1);

namespace core\kit\types;

use core\item\CustomItem;
use core\kit\Kit;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class OnceKit extends Kit {

    /**
     * Once constructor.
     */
    public function __construct() {
        $name = "§r§l§4Once§r ";
        $items =  [
            (new CustomItem(Item::DIAMOND_HELMET, $name . "§r§cHelmet§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_CHESTPLATE, $name . "§r§cChestplate§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_LEGGINGS, $name . "§r§7§r§cLeggings§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_BOOTS, $name . "§r§cBoots§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SWORD, $name . "§r§cSword§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 9),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5),
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_SHOVEL, $name . "§r§cShovel§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_PICKAXE, $name . "§r§cPickaxe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_AXE, $name . "§r§cAxe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 9),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            (new CustomItem(Item::DIAMOND_HOE, $name . "§r§cHoe§r", [], [
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 8),
                new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5)
            ]))->getItemForm(),
            Item::get(Item::STEAK, 0, 64),
            Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 5),
            Item::get(Item::GOLDEN_APPLE, 0, 32),
            Item::get(Item::WOOD, 0, 64),
            Item::get(Item::TORCH, 0, 64),
            Item::get(Item::OBSIDIAN, 0, 128),
            Item::get(Item::BEDROCK, 0, 128),
            Item::get(Item::BROWN_GLAZED_TERRACOTTA, 0, 1)
        ];
        parent::__construct("Once", self::COMMON, $items, 6000000);
    }
}