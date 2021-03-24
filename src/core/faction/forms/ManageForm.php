<?php

namespace core\faction\forms;

use core\Urbis;
use core\UrbisListener;
use core\CorePlayer;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use core\translation\Translation;
use core\faction\Claim;
use core\translation\TranslationException;
use core\command\CommandSender;
use core\command\utils\SubCommand;
use core\faction\Faction;
use core\faction\FactionManager;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ManageForm extends MenuForm
{
    public function __construct()
    {
        $title = TextFormat::AQUA.TextFormat::BOLD."Faction Manager";
		$text = TextFormat::GRAY."Options-";
		$options[] = new MenuOption("Faction Home");
		$options[] = new MenuOption("Claim Chunk");
		$options[] = new MenuOption("Faction Map");
		$options[] = new MenuOption("Member List");
		$options[] = new MenuOption("Delete Faction");
        parent::__construct($title, $text, $options);
    }

    public function onSubmit(Player $player, int $selectedOption): void
    {
        if (!$player instanceof CorePlayer) {
            return;
		}
		if($player->getFaction() == null){
			$player->sendMessage(Translation::getMessage("beInFaction"));
			return;
		}
		if($player->getFactionRole() != Faction::LEADER){
			$player->sendMessage(Translation::getMessage("noPermission"));
			return;
		}

		$factionManager = Urbis::getInstance()->getFactionManager();
		$faction = $player->getFaction();

		$option = $this->getOption($selectedOption)->getText();
		if($option === "Claim Chunk") {

			if($player->getLevel()->getName() !== Faction::CLAIM_WORLD) {
				$player->sendMessage(Translation::getMessage("noPermission"));
				return;
			}

			if($player->getFactionRole() === Faction::LEADER){

				if($factionManager->getClaimInPosition($player) !== null) {
					$player->sendMessage(Translation::getMessage("inClaim"));
					return;
				}

				$factionManager->addClaim(new Claim($player->getX() >> 4, $player->getZ() >> 4, $faction));
				$player->sendMessage("§a§l» §r§7You have successfully claimed the chunk you are currently inside.");
				return;
			} else {
				$player->sendMessage(Translation::getMessage("noPermission"));
				return;
			}
		}
		if($option === "Delete Faction"){
			if($player->getFactionRole() === Faction::LEADER){

				foreach($player->getFaction()->getOnlineMembers() as $players) {
					$players->addTitle(TextFormat::GREEN . TextFormat::BOLD . "Announcement", TextFormat::GRAY . $player->getFaction()->getName() . " has been disbanded", 20, 60, 20);
				}				

				$factionManager->removeFaction($player->getFaction()->getName());

			} else {
				$player->sendMessage(Translation::getMessage("noPermission"));
				return;
			}
		}
		if($option === "Member List") {
			$members = [];
			foreach($faction->getMembers() as $member) {
				if(($player = Urbis::getInstance()->getServer()->getPlayer($member)) !== null) {
					$members[] = TextFormat::AQUA . $player->getName();
					continue;
				}
				$members[] = TextFormat::WHITE . $member;
			}

			$player->sendMessage(TextFormat::GRAY . "Members: " . implode(TextFormat::GRAY . ", ", $members));
			return;

		}
		if($option === "Faction Home") {
			if($player->getFaction()->getHome() === null) {
				$player->sendMessage(Translation::getMessage("homeNotSet"));
				return;
			}
			if($player->isTeleporting()) {
				$player->sendMessage(Translation::getMessage("alreadyTeleporting", [
					"name" => "You are"
				]));
				return;
			}
			Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($player, $player->getFaction()->getHome(), 5), 20);
			return;
		}
		if($option === "Faction Map") {
			FactionManager::sendFactionMap($player);
			return;
		}
    }
}
