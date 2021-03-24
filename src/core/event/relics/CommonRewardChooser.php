<?php

declare(strict_types = 1);

namespace core\event\relics;

use core\crate\Reward;
use core\crate\Crate;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\HolyBox;
use core\item\types\SellWand;
use core\Urbis;
use core\CorePlayer;
use core\rank\Rank;
use core\item\types\ChestKit;
use core\item\types\CrateKeyNote;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\Player;

class CommonRewardChooser {

    /** @var Reward[] */
    private $rewards = [];

    /**
     * Crate constructor.
     */
    public function __construct() {
        $this->rewards = [
            new Reward( "$10,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(10000);
            }, 70),
            new Reward("$5,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(5000);
            }, 70),
            new Reward("Sell Wand", Item::get(Item::DIAMOND_HOE, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new SellWand(50))->getItemForm());
            }, 70),
            new Reward("x12 Iron Block", Item::get(Item::IRON_BLOCK, 0, 12), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::IRON_BLOCK, 0, 12));
            }, 89),
            new Reward("x3 Emerald Block", Item::get(Item::EMERALD_BLOCK, 0, 3), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::EMERALD_BLOCK, 0, 3));
            }, 89),
            new Reward("x12 Diamond Ore", Item::get(Item::DIAMOND, 0, 12), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::DIAMOND, 0, 12));
            }, 89),
            new Reward("x12 Iron Ingot", Item::get(Item::IRON_INGOT, 0, 12), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::IRON_INGOT, 0, 12));
            }, 89),
            new Reward("Noble Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Noble"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 70),
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
     * @return Reward
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