<?php

declare(strict_types=1);

namespace core\rank;

use core\Urbis;
use core\CorePlayer;
use core\UrbisListener;
use core\discord\DiscordManager;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;
use pocketmine\IPlayer;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;

class RankListener implements Listener{

	/** @var Urbis */
	private $core;
    
	/**
	 * GroupListener constructor.
	 *
	 * @param Urbis $core
	 */
	public function __construct(Urbis $core){
		$this->core = $core;
	}

	/**
	 * @priority HIGHEST
	 * @param PlayerChatEvent $event
	 */
	public function onPlayerChat(PlayerChatEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getPlayer();
		if(!$player instanceof CorePlayer){
			return;
		}
		$mode = $player->getChatMode();

		$webhook = null;
		if($mode === CorePlayer::PUBLIC){
			$webhook = "804487536205496351/3-Gs5uSR1kCUX1FUWEXibImqyrMZpUin04q5e4NyuTbybd8WwEs5hTq6JSuCPSa8Aea6";
		}elseif($mode === CorePlayer::STAFF){
			$webhook = "804487598768652323/klpx6j89pMJG8uR9DSblK0uBMKC9ZvpuODe5D7ZAStsbadg3ZpW9wYhHPxYgd02h3mwG";
		}
		if($webhook !== null)
			DiscordManager::postWebhook($webhook, $event->getMessage(), $player->getName() . " (" . $player->getRank()->getName() . ")");

		$faction = $player->getFaction();
		if($faction === null and ($mode === CorePlayer::FACTION or $mode === CorePlayer::ALLY)){
			$mode = CorePlayer::PUBLIC;
			$player->setChatMode($mode);
		}
		if($mode === CorePlayer::PUBLIC){
			$event->setFormat($player->getRank()->getChatFormatFor($player, $event->getMessage(), [
				"faction_rank" => $player->getFactionRoleToString(),
				"faction" => ($faction = $player->getFaction()) !== null ? $faction->getName() : "",
				"kills" => $player->getKills(),
			]));
			return;
		}
		$event->setCancelled();
		if($mode === CorePlayer::STAFF){
			/** @var CorePlayer $staff */
			foreach($this->core->getServer()->getOnlinePlayers() as $staff){
				$rank = $staff->getRank();
				if($rank->getIdentifier() >= Rank::TRAINEE and $rank->getIdentifier() <= Rank::OWNER){
					$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
				if($rank->getIdentifier() === Rank::DEVELOPER){
					$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
				if($rank->getIdentifier() === Rank::BUILDER){
					$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
			}
			return;
		}
		if($player->getChatMode() === CorePlayer::FACTION){
			$onlinePlayers = $faction->getOnlineMembers();
			foreach($onlinePlayers as $onlinePlayer){
				$onlinePlayer->sendMessage("§l§a(FC)§r " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
			}
		}else{
			$allies = $faction->getAllies();
			$onlinePlayers = $faction->getOnlineMembers();
			foreach($allies as $ally){
				if(($ally = $this->core->getFactionManager()->getFaction($ally)) === null){
					continue;
				}
				$onlinePlayers = array_merge($ally->getOnlineMembers(), $onlinePlayers);
			}
			foreach($onlinePlayers as $onlinePlayer){
				$onlinePlayer->sendMessage("§l§6(" . TextFormat::BOLD .  "§l§6AC)§r " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
			}
		}
	}

	/**
	 * @priority NORMAL
	 * @param EntityRegainHealthEvent $event
	 */
	public function onEntityRegainHealth(EntityRegainHealthEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getEntity();
		if(!$player instanceof CorePlayer){
			return;
		}
		//$player->setScoreTag(TextFormat::WHITE . round($player->getHealth(), 1) . " §l§c♥§r");
	}

	/**
	 * @priority NORMAL
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getEntity();
		if(!$player instanceof CorePlayer){
			return;
		}
		//$player->setScoreTag(TextFormat::WHITE . round($player->getHealth(), 1) . " §l§c♥§r");
	}
}
