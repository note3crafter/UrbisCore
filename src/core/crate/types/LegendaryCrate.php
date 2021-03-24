<?php

declare(strict_types = 1);

namespace core\crate\types;

use core\command\types\MaskCommand;
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
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\utils\TextFormat;

class LegendaryCrate extends Crate {

    /**
     * LegendaryCrate constructor.
     *
     * @param Position $position\
     */
    public function __construct(Position $position) {
        parent::__construct(self::LEGENDARY, $position, [
            new Reward("$50,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(50000);
            }, 50),
            new Reward("$100,000", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->addToBalance(100000);
            }, 50),
            new Reward("7,000 XP Note", Item::get(Item::PAPER, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new XPNote(7000))->getItemForm());
            }, 100),
            new Reward("Iron Golem Spawner", Item::get(Item::MOB_SPAWNER, 0, 1), function(CorePlayer $player): void {
                $item = Item::get(Item::MOB_SPAWNER, 0, 1, new CompoundTag("", [
                    new IntTag("EntityId", Entity::IRON_GOLEM)
                ]));
                $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . "Iron Golem Spawner");
                $player->getInventory()->addItem($item);
            }, 5),
            new Reward("x64 Obsidian", Item::get(Item::OBSIDIAN, 0, 64), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::OBSIDIAN, 0, 64));
            }, 89),
            new Reward("x32 Enchanted Golden Apple", Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 32));
            }, 79),
            new Reward("Mercenary Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Mercenary"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 85),
            new Reward("Titan Kit", Item::get(Item::CHEST_MINECART, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Titan"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 85),
            new Reward("Enchantment", Item::get(Item::ENCHANTED_BOOK, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm());
            }, 100),
            new Reward("x64 TNT", Item::get(Item::TNT, 0, 64), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::TNT, 0, 64));
            }, 93),
            new Reward("Sell Wand (Uses: 100)", Item::get(Item::DIAMOND_HOE, 0, 1), function(CorePlayer $player): void {
                $player->getInventory()->addItem((new SellWand(100))->getItemForm());
            }, 90),
            new Reward("Mask Charm", ($item = MaskCommand::getMaskCharmItem()), function(CorePlayer $player)use($item): void {
                $player->getInventory()->addItem($item);
            }, 16),
            new Reward("x5 Epic Crate Keys", Item::get(Item::PAPER), function(CorePlayer $player): void {
                $crate = Urbis::getInstance()->getCrateManager()->getCrate("Epic");
                $player->addKeys($crate, 5);
            }, 36)
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
        $player->addFloatingText(Position::fromObject($this->getPosition()->add(0.5, 1.25, 0.5), $this->getPosition()->getLevel()), $this->getName(), "§l§6Legendary §r§7Crate§r\n§7(Right Click)\n§6x" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§6Legendary §r§7Crate§r\n§7(Right Click)\n§6x" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§6" . $reward->getName());
        $text->sendChangesTo($player);
    }
}
