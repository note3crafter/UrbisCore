<?php

declare(strict_types = 1);

namespace core\crate\types;

use core\crate\Crate;
use core\crate\Reward;
use core\item\ItemManager;
use core\item\types\ChestKit;
use core\item\types\EnchantmentBook;
use core\item\types\SellWand;
use core\item\types\XPNote;
use core\Urbis;
use core\CorePlayer;
use core\utils\UtilsException;
use pocketmine\item\Item;
use pocketmine\level\Position;

class RareCrate extends Crate {

    /**
     * RareCrate constructor.
     * @param Position $position
     */
    public function __construct(Position $position) {
        $tag = Urbis::getInstance()->getTagManager();
        parent::__construct(self::RARE, $position, [
            new Reward("$5,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(5000);
            }, 50),
            new Reward("$10,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(10000);
            }, 50),
            new Reward("3,000 XP Note", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new XPNote(3000))->getItemForm());
            }, 100),
            new Reward("x32 Obsidian", Item::get(Item::OBSIDIAN, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::OBSIDIAN, 0, 32));
            }, 85),
            new Reward("x5 Enchanted Golden Apple", Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 5), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 5));
            }, 79),
            new Reward("x32 Golden Apple", Item::get(Item::GOLDEN_APPLE, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 32));
            }, 99),
            new Reward("Enchantment", Item::get(Item::ENCHANTED_BOOK, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm());
            }, 100),
            new Reward("Enderlord Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Enderlord"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 100),
            new Reward("Notrix Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Notrix"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 75),
            new Reward("x32 TNT", Item::get(Item::TNT, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::TNT, 0, 32));
            }, 91),
            new Reward("Sell Wand (Uses: 10)", Item::get(Item::DIAMOND_HOE, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new SellWand(10))->getItemForm());
            }, 90),
            new Reward("x2 Epic Crate Keys", Item::get(Item::STRING), function(CorePlayer $player): void {
                $crate = Urbis::getInstance()->getCrateManager()->getCrate("Epic");
                $player->addKeys($crate, 2);
            }, 36),
            new Reward("Random Tag", $tag->getTagNote("Urbis"), function(CorePlayer $player)use($tag): void {
                $player->getInventory()->addItem($tag->getTagNote("Urbis"));
            }, 45),
            new Reward("Random Tag", $tag->getTagNote("Agro"), function(CorePlayer $player)use($tag): void {
                $player->getInventory()->addItem($tag->getTagNote("Agro"));
            }, 45)
        ]);
    }

    /**
     * @param CorePlayer $player
     *
     * @throws UtilsException
     */
    public function spawnTo(CorePlayer $player): void {
        $particle = $player->getFloatingText($this->getName());
        if($particle !== null) {
            return;
        }
        $player->addFloatingText(Position::fromObject($this->getPosition()->add(0.5, 1.25, 0.5), $this->getPosition()->getLevel()), $this->getName(), "§l§bRare §r§7Crate§r\n§7(Right Click)\n§bx" . $player->getKeys($this) . " §7Keys!§r");
    }

    /**
     * @param CorePlayer $player
     *
     * @throws UtilsException
     */
    public function updateTo(CorePlayer $player): void {
        $particle = $player->getFloatingText($this->getName());
        if($particle === null) {
            $this->spawnTo($player);
        }
        $text = $player->getFloatingText($this->getName());
        $text->update("§l§bRare §r§7Crate§r\n§7(Right Click)\n§bx" . $player->getKeys($this) . " §7Keys!§r");
        $text->sendChangesTo($player);
    }

    /**
     * @param CorePlayer $player
     */
    public function despawnTo(CorePlayer $player): void {
        $particle = $player->getFloatingText($this->getName());
        if($particle !== null) {
            $particle->despawn($player);
        }
    }

    /**
     * @param Reward        $reward
     * @param CorePlayer $player
     *
     * @throws UtilsException
     */
    public function showReward(Reward $reward, CorePlayer $player): void {
        $particle = $player->getFloatingText($this->getName());
        if($particle === null) {
            $this->spawnTo($player);
        }
        $text = $player->getFloatingText($this->getName());
        $text->update("§l§b" . $reward->getName());
        $text->sendChangesTo($player);
    }
}
