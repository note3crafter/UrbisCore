<?php


namespace core\crate\types;


use core\crate\Reward;
use core\Urbis;
use core\CorePlayer;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\XPNote;
use core\kit\Kit;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class MonthlyCrate
{
    /** @var array  */
    private $rewards = [];

    public const PREFIX = "§l§cDECEMBER§r §bMonthly §7Crate";

    public function __construct()
    {
        $this->rewards = [
            new Reward("CE Books", Item::get(Item::ENCHANTED_BOOK, 0, 1), function (CorePlayer $player): void {
                $player->getInventory()->addItem((new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm());
            }, 15),
            new Reward("x3 Legendary Crate Keys", Item::get(Item::PAPER), function (CorePlayer $player): void {
                $crate = Urbis::getInstance()->getCrateManager()->getCrate("Legendary");
                $player->addKeys($crate, 3);
            }, 19),
            new Reward("$250,000", Item::get(Item::PAPER, 0, 1), function (CorePlayer $player): void {
                $player->addToBalance(250000);
            }, 18),
            new Reward("500 XP Note", Item::get(Item::PAPER, 0, 5), function (CorePlayer $player): void {
                $player->getInventory()->addItem((new XPNote(500))->getItemForm()->setCount(5));
            }, 17),
            new Reward("Random Kit", Item::get(Item::PAPER, 0, 1), function (CorePlayer $player): void {
                $kits = Urbis::getInstance()->getKitManager()->getSacredKits();
                /** @var Kit $kit */
                $kit = $kits[array_rand($kits)];
                $kit->giveTo($player);
            }, 10),
        ];
    }


    /**
     * @return Reward[]
     */
    public function getRewards(): array
    {
        return $this->rewards;
    }

    /**
     * @param int $loop
     *
     * @return Reward
     */
    public function getReward(int $loop = 0): Reward
    {
        $chance = mt_rand(0, 100);
        $reward = $this->rewards[array_rand($this->rewards)];
        if ($loop >= 10) {
            return $reward;
        }
        if ($reward->getChance() <= $chance) {
            return $this->getReward($loop + 1);
        }
        return $reward;
    }

}