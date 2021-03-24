<?php

declare(strict_types = 1);

namespace core\faction;

use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class FactionListener implements Listener {

    /** @var Urbis */
    private $core;

    /**
     * UrbisListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
    }

    /**
     * @priority HIGHEST
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null) {
            return;
        }
        foreach($faction->getOnlineMembers() as $member) {
            $member->sendMessage("§l§a(!)§r §7{$player->getName()} is now online!");
        }
    }

    /**
     * @priority HIGHEST
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null) {
            return;
        }
        foreach($faction->getOnlineMembers() as $member) {
            $member->sendMessage("§l§c(!)§r §7{$player->getName()} is now offline!");
        }
    }

    /**
     * @priority HIGH
     * @param PlayerDeathEvent $event
     */
    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $cause = $player->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if($damager instanceof CorePlayer) {
                $faction = $player->getFaction();
                if($faction !== null) {
                    $faction->subtractStrength(Faction::POWER_PER_KILL);
                }
                $damagerFaction = $damager->getFaction();
                if($damagerFaction !== null) {
                    $damagerFaction->addStrength(Faction::POWER_PER_KILL);
                }
                return;
            }
        }
    }

    /**
     * @priority LOWEST
     * @param EntityDamageEvent $event
     *
     * @throws TranslationException
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if($entity instanceof CorePlayer) {
            $faction = $entity->getFaction();
            if($faction === null) {
                return;
            }
            if($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                if(!$damager instanceof CorePlayer) {
                    return;
                }
                $damagerFaction = $damager->getFaction();
                if($damagerFaction === null) {
                    return;
                }
                if($faction->isInFaction($damager)) {
                    $damager->sendMessage(Translation::getMessage("attackFactionAssociate"));
                    $event->setCancelled();
                    return;
                }
                if($damager->isTagged()) {
                    $damager->combatTag(false);
                }
				if($faction->isAlly($damagerFaction)){
					$damager->sendMessage("§8§l(§c!§8) §r§7You may not attack a faction ally!");
					$event->setCancelled();
					return;
				}
            }
        }
    }

    /**
     * @priority LOWEST
     *
     * @param BlockPlaceEvent $event
     *
     * @throws TranslationException
     */
    public function onBlockPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $claim = $this->core->getFactionManager()->getClaimInPosition($block->asPosition());
        if($claim === null) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null or $claim->getFaction()->getName() !== $faction->getName() or $player->getFactionRole() === Faction::RECRUIT) {
            $player->sendPopUp(Translation::getMessage("editClaimNotAllowed"));
            $event->setCancelled();
            return;
        }
    }

    /**
     * @priority LOWEST
     * @param BlockBreakEvent $event
     *
     * @throws TranslationException
     */
    public function onBlockBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $claim = $this->core->getFactionManager()->getClaimInPosition($block->asPosition());
        if($claim === null) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null or $claim->getFaction()->getName() !== $faction->getName() or $player->getFactionRole() === Faction::RECRUIT) {
            $player->sendMessage(Translation::getMessage("editClaimNotAllowed"));
            $event->setCancelled();
            return;
        }
    }
}