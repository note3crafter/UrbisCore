<?php

declare(strict_types = 1);

namespace core\event\misc;

use core\crate\Reward;
use core\crate\Crate;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\HolyBox;
use core\item\types\SellWand;
use core\Urbis;
use core\CorePlayer;
use core\command\types\MaskCommand;
use core\rank\Rank;
use core\item\types\CrateKeyNote;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\Player;

class LootBagReward {

    /** @var Reward[] */
    private $rewards = [];

    /**
     * Crate constructor.
     */
    public function __construct() {
        $this->rewards = [
            new Reward("Mask Charm", ($item = MaskCommand::getMaskCharmItem()), function(CorePlayer $player)use($item): void {
                $player->getInventory()->addItem($item);
            }, 70),
            new Reward("Common Key Note", Item::get(Item::PAPER, 0, 3), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new CrateKeyNote($player->getCore()->getCrateManager()->getCrate(Crate::COMMON), mt_rand(2, 5)))->getItemForm());
            }, 120),
            new Reward("Legendary Key Note", Item::get(Item::PAPER, 0, 3), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new CrateKeyNote($player->getCore()->getCrateManager()->getCrate(Crate::LEGENDARY), mt_rand(2, 5)))->getItemForm());
            }, 120),
            new Reward("$150,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(150000);
            }, 100),
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