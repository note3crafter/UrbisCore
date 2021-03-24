<?php

declare(strict_types = 1);

namespace core\crate\types;

use core\crate\Crate;
use core\crate\Reward;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\XPNote;
use core\CorePlayer;
use core\item\types\ChestKit;
use core\item\types\SellWand;
use core\Urbis;
use core\utils\UtilsException;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\utils\TextFormat;

class VoteCrate extends Crate {

    /**
     * VoteCrate constructor.
     * @param Position $position
     */
    public function __construct(Position $position) {
        $tag = Urbis::getInstance()->getTagManager();
        parent::__construct(self::VOTE, $position, [
            new Reward("$1,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(1000);
            }, 50),
            new Reward("$2,500", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(2000);
            }, 50),
            new Reward("$5,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(2000);
            }, 50),
            new Reward("$10,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(2000);
            }, 50),
            new Reward("1,000 XP Note", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new XPNote(1000))->getItemForm());
            }, 100),
            new Reward("Pig Spawner", Item::get(Item::MOB_SPAWNER, 0, 1), function(CorePlayer $player): void {
                $item = Item::get(Item::MOB_SPAWNER, 0, 1, new CompoundTag("", [
                    new IntTag("EntityId", Entity::PIG)
                ]));
                $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . "Pig Spawner");
                $player->getInventory()->addItem($item);
            }, 25),
            new Reward("x32 Obsidian", Item::get(Item::OBSIDIAN, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::OBSIDIAN, 0, 32));
            }, 85),
            new Reward("x5 Enchanted Golden Apple", Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 5), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 5));
            }, 99),
            new Reward("Enderlord Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Enderlord"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 100),
            new Reward("Titan Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Titan"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 75),
            new Reward("Enchantment", Item::get(Item::ENCHANTED_BOOK, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm());
            }, 25),
            new Reward("x16 TNT", Item::get(Item::TNT, 0, 16), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::TNT, 0, 16));
            }, 89),
            new Reward("Sell Wand (Uses: 25)", Item::get(Item::DIAMOND_HOE, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new SellWand(25))->getItemForm());
            }, 50),
            new Reward("x2 Common Crate Keys", Item::get(Item::STRING), function(CorePlayer $player): void {
                $crate = Urbis::getInstance()->getCrateManager()->getCrate("Common");
                $player->addKeys($crate, 2);
            }, 36),
            new Reward("Random Tag", $tag->getTagNote("Simp"), function(CorePlayer $player)use($tag): void {
                $player->getInventory()->addItem($tag->getTagNote("Simp"));
            }, 45),
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
        $player->addFloatingText(Position::fromObject($this->getPosition()->add(0.5, 1.25, 0.5), $this->getPosition()->getLevel()), $this->getName(), "§l§cVote §r§7Crate§r\n§7(Right Click)\n§cx" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§cVote §r§7Crate§r\n§7(Right Click)\n§cx" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§c" . $reward->getName());
        $text->sendChangesTo($player);
    }
}
