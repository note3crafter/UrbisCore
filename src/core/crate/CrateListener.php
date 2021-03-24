<?php

declare(strict_types=1);

namespace core\crate;

use core\Urbis;
use core\CorePlayer;
use core\translation\TranslationException;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\tile\Chest;

class CrateListener implements Listener
{

    /** @var Urbis */
    private $core;

    /**
     * CrateListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core)
    {
        $this->core = $core;
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        foreach ($this->core->getCrateManager()->getCrates() as $crate) {
            $crate->spawnTo($player);
        }
    }

    /**
     * @priority LOWEST
     * @param PlayerInteractEvent $event
     *
     * @throws TranslationException
     */
    public function onPlayerInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player instanceof CorePlayer) {
            return;
        }
        $block = $event->getBlock();
        foreach ($this->core->getCrateManager()->getCrates() as $crate) {
            if ($crate->getPosition()->equals($block->asPosition())) {
                $crate->try($player);
                $event->setCancelled();
            }
        }
    }
}