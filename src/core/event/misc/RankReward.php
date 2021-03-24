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

class RankReward {

    /** @var Reward[] */
    private $rewards = [];

    /**
     * Crate constructor.
     */
    public function __construct() {
        $this->rewards = [
            new Reward("Titan Rank", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->setRank($player->getCore()->getRankManager()->getRankByIdentifier(Rank::TITAN));
            }, 50),
            new Reward("Lightborn Rank", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->setRank($player->getCore()->getRankManager()->getRankByIdentifier(Rank::LIGHTBORN));
            }, 150),
            new Reward("Miercenary Rank", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->setRank($player->getCore()->getRankManager()->getRankByIdentifier(Rank::MERCENARY));
            }, 250),
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
            return $this->getReward($loop + 1);
        }
        return $reward;
    }
}