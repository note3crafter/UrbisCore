<?php

declare(strict_types = 1);

namespace core\faction\command\subCommands;

use core\command\utils\SubCommand;
use core\faction\Faction;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class LeaderSubCommand extends SubCommand {

    /**
     * LeaderSubCommand constructor.
     */
    public function __construct() {
        parent::__construct("leader", "/faction leader <player>");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        if($sender->getFaction() === null) {
            $sender->sendMessage(Translation::getMessage("beInFaction"));
            return;
        }
        if(!isset($args[1])) {
            $sender->sendMessage(Translation::getMessage("usageMessage", [
                "usage" => $this->getUsage()
            ]));
            return;
        }
        $player = $this->getCore()->getServer()->getPlayer($args[1]);
        if(!$player instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("invalidPlayer"));
            return;
        }
        if($player->getFaction() == null){
            $sender->sendMessage(TextFormat::RED . "You are not in a faction.");
            return;
        }
        if((!$player->getFaction()->isInFaction($sender)) or $player->getFaction() === null or $player->getName() == $sender->getName()) {
            $sender->sendMessage(Translation::getMessage("invalidPlayer"));
            return;
        }
        if($sender->getFactionRole() !== Faction::LEADER) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }
        $sender->getFaction()->removeMember($player->getName());
        $sender->setFactionRole(Faction::OFFICER);
        $player->setFactionRole(Faction::LEADER);
        $player->getSession()->setFaction($sender->getFaction()->getName());
        $sender->getFaction()->addMember($sender);
        foreach($sender->getFaction()->getOnlineMembers() as $member) {
            $member->sendMessage(Translation::getMessage("promotion", [
                "name" => TextFormat::GREEN . $player->getName(),
                "position" => TextFormat::GOLD . "leader"
            ]));
        }
    }
}