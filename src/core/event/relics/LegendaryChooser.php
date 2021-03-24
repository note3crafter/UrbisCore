<?php

declare(strict_types = 1);

namespace core\event\relics;

use core\crate\Reward;
use core\crate\Crate;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\HolyBox;
use core\item\types\SellWand;
use core\event\EventManager;
use core\Urbis;
use core\CorePlayer;
use core\item\types\ChestKit;
use core\rank\Rank;
use core\tag\tags;
use core\item\types\CrateKeyNote;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\Player;

class LegendaryChooser {

    /** @var Reward[] */
    private $rewards = [];

    /**
     * Crate constructor.
     */
    public function __construct() {
        $this->rewards = [
            new Reward( "$12,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(12000);
            }, 100),
            new Reward("$7,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(7000);
            }, 100),
            new Reward("Sell Wand", Item::get(Item::DIAMOND_HOE, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new SellWand(50))->getItemForm());
            }, 100),
            new Reward("Enchantment", Item::get(Item::ENCHANTED_BOOK, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm());
            }, 80),
            new Reward("Common Key Note", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new CrateKeyNote($player->getCore()->getCrateManager()->getCrate(Crate::COMMON), mt_rand(2, 5)))->getItemForm());
            }, 120),
            new Reward("Zeus Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Zeus"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 100),
            new Reward("Enderlord Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Enderlord"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 100),
            new Reward("x16 Diamond Block", Item::get(Item::DIAMOND_BLOCK, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::DIAMOND_BLOCK, 0, 32));
            }, 89),
            new Reward("x8 Emerald Block", Item::get(Item::EMERALD_BLOCK, 0, 8), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::EMERALD_BLOCK, 0, 8));
            }, 89),
            new Reward("x12 Iron Ingot", Item::get(Item::IRON_INGOT, 0, 12), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::IRON_INGOT, 0, 12));
            }, 89),
            new Reward("Mercenary Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Mercenary"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 150)
        ];
    }

    /**
     * @return Reward[]
     */
    public function getRewards(): array {
        return $this->rewards;
    }

    /**
     * @param int $loop
     *
     * @return TreasureChooser
     */
    public function getReward(int $loop = 0): Reward {
        $chance = mt_rand(0, 1000);
        $reward = $this->rewards[array_rand($this->rewards)];
        if($loop >= 20) {
            return $reward;
        }
        if($reward->getChance() <= $chance) {
            return $this->getReward($loop + 2);
        }
        return $reward;
    }
}