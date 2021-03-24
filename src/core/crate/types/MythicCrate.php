<?php

declare(strict_types = 1);

namespace core\crate\types;

use core\crate\Crate;
use core\crate\Reward;
use core\item\types\ChestKit;
use pocketmine\item\Item;
use core\Urbis;
use core\CorePlayer;
use core\utils\UtilsException;
use pocketmine\level\Position;

class MythicCrate extends Crate {

    /**
     * MythicCrate constructor.
     *
     * @param Position $position
     */
    public function __construct(Position $position) {
        $tag = Urbis::getInstance()->getTagManager();
        parent::__construct(self::MYTHIC, $position, [
        new Reward("Random Tag", $tag->getTagNote("Urbis"), function(CorePlayer $player)use($tag): void {
                $player->getInventory()->addItem($tag->getTagNote("Urbis"));
            }, 45),
        new Reward("x32 Enchanted Golden Apple", Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 32));
            }, 79),
        new Reward("Mercenary Kit", Item::get(Item::ENDER_CHEST, 0, 1), function(CorePlayer $player): void {
                $item = new ChestKit(Urbis::getInstance()->getKitManager()->getKitByName("Mercenary"));
                $player->getInventory()->addItem($item->getItemForm());
            }, 85),
        new Reward("x32 Obsidian", Item::get(Item::OBSIDIAN, 0, 32), function(CorePlayer $player): void {
                $player->getInventory()->addItem(Item::get(Item::OBSIDIAN, 0, 32));
            }, 85),
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
        $player->addFloatingText(Position::fromObject($this->getPosition()->add(0.5, 1.25, 0.5), $this->getPosition()->getLevel()), $this->getName(), "§l§dMythic §r§7Crate§r\n§7(Right Click)\n§dx" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§dMythic §r§7Crate§r\n§7(Right Click)\n§dx" . $player->getKeys($this) . " §7Keys!§r");
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
        $text->update("§l§d" . $reward->getName());
        $text->sendChangesTo($player);
    }
}
