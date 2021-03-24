<?php

declare(strict_types = 1);

namespace core\combat;

use core\combat\boss\ArtificialIntelligence;
use core\item\types\Drops;
use core\Urbis;
use core\CorePlayer;
use core\rank\Rank;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\entity\EffectInstance;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\types\GameMode;
use pocketmine\scheduler\Task;
use pocketmine\entity\Effect;
use pocketmine\utils\TextFormat;

class CombatListener implements Listener {

    /** @var int[] */
    public $godAppleCooldown = [];

    /** @var int[] */
    public $goldenAppleCooldown = [];

    /** @var int[] */
    public $enderPearlCooldown = [];

    /** @var Urbis */
    private $core;

    private const WHITELISTED = [
        "/mute",
        "/kick",
        "/unban",
        "/freeze",
        "/tempban",
        "/tempblock",
        "/tempmute",
    ];

    /**
     * CombatListener constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     * @param PlayerCommandPreprocessEvent $event
     *
     * @throws TranslationException
     */
    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        if($player->getRank()->getIdentifier() >= Rank::TRAINEE and $player->getRank()->getIdentifier() <= Rank::OWNER && in_array($event->getMessage(), self::WHITELISTED)) {
            return;
        }
        if(strpos($event->getMessage(), "/") !== 0) {
            return;
        }
        if(in_array(explode(" ", $event->getMessage())[0], self::WHITELISTED)) {
            return;
        }
        if($player->isTagged()) {
            $player->sendMessage(Translation::getMessage("noPermissionCombatTag"));
            $event->setCancelled();
        }
    }

    /**
     * @priority LOW
     * @param PlayerItemConsumeEvent $event
     *
     * @throws TranslationException
     */
    public function onPlayerItemConsume(PlayerItemConsumeEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getId() === Item::ENCHANTED_GOLDEN_APPLE) {
            if(isset($this->godAppleCooldown[$player->getRawUniqueId()])) {
                if((time() - $this->godAppleCooldown[$player->getRawUniqueId()]) < 42) {
                    if(!$event->isCancelled()) {
                        $time = 50 - (time() - $this->godAppleCooldown[$player->getRawUniqueId()]);
                        $time = TextFormat::RED . $time . TextFormat::GRAY;
                        $player->sendMessage("§c§l(!)§r §8» §r§7Slow down! This action is on cooldown for §c$time §7seconds!§r");
                        $event->setCancelled();
                        return;
                    }
                }
                $this->godAppleCooldown[$player->getRawUniqueId()] = time();
                return;
            }
            $this->godAppleCooldown[$player->getRawUniqueId()] = time();
            return;
        }
        if($item->getId() === Item::GOLDEN_APPLE) {
            if(isset($this->goldenAppleCooldown[$player->getRawUniqueId()])) {
                if((time() - $this->goldenAppleCooldown[$player->getRawUniqueId()]) < 5) {
                    if(!$event->isCancelled()) {
                        $time = 6 - (time() - $this->goldenAppleCooldown[$player->getRawUniqueId()]);
                        $time = TextFormat::RED . $time . TextFormat::GRAY;
                        $player->sendMessage("§c§l(!)§r §8» §r§7Slow down! This action is on cooldown for §c$time §7seconds!§r");
                        $event->setCancelled();
                        return;
                    }
                }
                $this->goldenAppleCooldown[$player->getRawUniqueId()] = time();
                return;
            }
            $this->goldenAppleCooldown[$player->getRawUniqueId()] = time();
            return;
        }
    }

    /**
     * @priority NORMAL
     * @param PlayerRespawnEvent $event
     */
    public function onPlayerRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        $level = $player->getServer()->getDefaultLevel();
        $spawn = $level->getSpawnLocation();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $this->core->getScheduler()->scheduleDelayedTask(new class($player, $spawn) extends Task {

            /** @var CorePlayer */
            private $player;

            /** @var Position */
            private $position;

            /**
             *  constructor.
             *
             * @param CorePlayer $player
             * @param Position      $position
             */
            public function __construct(CorePlayer $player, Position $position) {
                $this->player = $player;
                $this->position = $position;
            }

            /**
             * @param int $currentTick
             */
            public function onRun(int $currentTick) {
                if(!$this->player->isClosed()) {
                    $this->player->teleport($this->position);
                }
            }
        }, 1);
    }

    /**
     * @priority LOW
     * @param PlayerDeathEvent $event
     *
     * @throws TranslationException
     */
    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $cause = $player->getLastDamageCause();
        $message = Translation::getMessage("death", [
            "name" => $player->getName(),
        ]);
        if($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if($killer instanceof CorePlayer) {
                $killer->addKills();
                $message = Translation::getMessage("deathByPlayer", [
                    "name" => $player->getName(),
                    "killer" => $killer->getName()
                ]);
            }
        }
        $player->combatTag(false);
        $event->setDeathMessage($message);
    }

    /**
     * @priority NORMAL
     * @param PlayerMoveEvent $event
     *
     * @throws TranslationException
     */
    public function onPlayerMove(PlayerMoveEvent $event): void {
        $to = $event->getTo();
        $areas = $this->core->getAreaManager()->getAreasInPosition($to);
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        if($player->getLevel()->getName() === "koth")
        {

            if($player->isFlying())
            {

                $player->setFlying(false);
                $player->setAllowFlight(false);

            }

        }
        if(!$player->isTagged()) {
            return;
		}
		if($player->isTagged()) {
			if($player->isFlying(true) or $player->getAllowFlight(true) and $player->isSurvival()) {
				$player->setFlying(false);
				$player->setAllowFlight(false);
			}
		}
        if($areas === null) {
            return;
        }
        foreach($areas as $area) {
            if($area->getPvpFlag() === false) {
                $event->setCancelled();
                $player->sendMessage(Translation::getMessage("enterSafeZoneInCombat"));
                return;
            }
        }
    }

    /**
     * @priority NORMAL
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        if($player->isTagged()) {
            $player->setHealth(0);
        }
    }

    /**
     * @priority HIGH
     * @param PlayerInteractEvent $event
     */
    public function onPlayerInteract(PlayerInteractEvent $event) {
        if($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        if(!$player instanceof CorePlayer) {
            return;
        }
        $item = $event->getItem();
        if($item->getId() === Item::ENDER_PEARL) {
            if(!isset($this->enderPearlCooldown[$player->getRawUniqueId()])) {
                $this->enderPearlCooldown[$player->getRawUniqueId()] = time();
                return;
            }
            if(time() - $this->enderPearlCooldown[$player->getRawUniqueId()] < 10) {
                $event->setCancelled();
                return;
            }
            $this->enderPearlCooldown[$player->getRawUniqueId()] = time();
            return;
        }
    }

    /**
     * @priority HIGHEST
     * @param EntityDamageEvent $event
     *
     * @throws TranslationException
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        if($event->isCancelled()) {
            return;
        }
		$entity = $event->getEntity();
		if($entity == null) {
			return;
		}
        if($entity instanceof CorePlayer) {
			if($entity->getHealth() <= 6) {
				if(!$entity->hasEffect(Effect::REGENERATION)) {
					if($entity->getClass() == "Tank") {
						$entity->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 3 * 20, 1));
					}
				}
			}
            if($event->getCause() === EntityDamageEvent::CAUSE_FALL and ($entity->getLevel()->getFolderName() === "Spawn")) {
                $event->setCancelled();
                return;
			}
            if($event instanceof EntityDamageByEntityEvent) {
				$damager = $event->getDamager();
				if(!$damager instanceof CorePlayer) {
					return;
				}
				if($damager == null) {
					return;
				}
                if($entity->isTagged()) {
                    $entity->combatTag();
                } else {
                    $entity->combatTag();
                    $entity->sendMessage(Translation::getMessage("combatTag"));
                }
                if($damager->isTagged()) {
                    $damager->combatTag();
                }
                else {
                    $damager->combatTag();
                    $damager->sendMessage(Translation::getMessage("combatTag"));
                }
                if($entity->isFlying() or $entity->getAllowFlight() and $entity->isSurvival()) {
                    $entity->setFlying(false);
                    $entity->setAllowFlight(false);
                    $entity->sendMessage(Translation::getMessage("flightToggle"));
                }
                if($damager->isFlying() or $damager->getAllowFlight() and $damager->isSurvival()) {
                    $damager->setFlying(false);
                    $damager->setAllowFlight(false);
                    $damager->sendMessage(Translation::getMessage("flightToggle"));
                }
            }
        }
	}

    /**
     * @priority HIGH
     * @param EntityTeleportEvent $event
     *
     * @throws TranslationException
     */
    public function onEntityTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if(!$entity instanceof CorePlayer || $entity->getGamemode() === GameMode::CREATIVE) {
            return;
        }
        if(!$entity->isTagged()) {
            return;
        }
        $to = $event->getTo();
        if($to->getLevel() === null) {
            return;
        }
        $areas = $this->core->getAreaManager()->getAreasInPosition($to);
        if($areas === null) {
            return;
        }
        foreach($areas as $area) {
            if($area->getPvpFlag() === false) {
                $event->setCancelled();
                $entity->sendMessage(Translation::getMessage("enterSafeZoneInCombat"));
            }
        }
    }
}