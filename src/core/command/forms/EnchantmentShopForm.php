<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\item\enchantment\Enchantment;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use core\libs\form\CustomForm;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EnchantmentShopForm extends MenuForm {

    /**
     * EnchantmentShopForm constructor.
     *
     * @param CorePlayer $player
     */
    public function __construct(CorePlayer $player) {
        $title = "§l§dEnchantments";
        $text = "XP Levels: " . $player->getXpLevel();
        $options = [];
        $options[] = new MenuOption("Common Enchantment 10 XP LVL");
        $options[] = new MenuOption("Uncommon Enchantment 25 XP LVL");
        $options[] = new MenuOption("Rare Enchantment 50 XP LVL");
        $options[] = new MenuOption("Mythic Enchantment 80 XP LVL");
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     *
     * @throws TranslationException
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        if(!$player instanceof CorePlayer) {
            return;
        }
        $option = $this->getOption($selectedOption);
        if($player->getInventory()->getSize() === count($player->getInventory()->getContents())) {
            $player->sendMessage(Translation::getMessage("fullInventory"));
            return;
        }
        switch($option->getText()) {
            case "Common Enchantment 10 XP LVL":
                $levels = 10;
                $rarity = Enchantment::RARITY_COMMON;
                break;
            case "Uncommon Enchantment 25 XP LVL":
                $levels = 25;
                $rarity = Enchantment::RARITY_UNCOMMON;
                break;
            case "Rare Enchantment 50 XP LVL":
                $levels = 50;
                $rarity = Enchantment::RARITY_RARE;
                break;
            case "Mythic Enchantment 80 XP LVL":
                $levels = 80;
                $rarity = Enchantment::RARITY_MYTHIC;
                break;
            default:
                return;
        }
        if($player->getXpLevel() < $levels) {
            $player->sendMessage(Translation::getMessage("notEnoughLevels"));
            return;
        }
        $item = (new EnchantmentBook(ItemManager::getRandomEnchantment($rarity)))->getItemForm();
        $player->subtractXpLevels($levels);
        $player->sendMessage(Translation::getMessage("buy", [
            "amount" => TextFormat::GREEN . "1",
            "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
            "price" => TextFormat::LIGHT_PURPLE . "$levels XP levels",
        ]));
        $player->getInventory()->addItem($item);
    }
}