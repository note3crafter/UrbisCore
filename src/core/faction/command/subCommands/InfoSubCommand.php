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

class InfoSubCommand extends SubCommand {

    /**
     * InfoSubCommand constructor.
     */
    public function __construct() {
        parent::__construct("info", "/faction info [faction]");
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
        if(!isset($args[1])) {
            if($sender->getFaction() === null) {
                $sender->sendMessage(Translation::getMessage("beInFaction"));
                return;
            }
            $faction = $sender->getFaction();
        }
        else {
            $faction = $this->getCore()->getFactionManager()->getFaction($args[1]);
            if($faction === null) {
                $sender->sendMessage(Translation::getMessage("invalidFaction"));
                return;
            }
        }
        $sender->sendMessage("§l§6[§r §f" . $faction->getName() . " §l§6]§r §6[§f" . count($faction->getMembers()) . "§8/§f" . Faction::MAX_MEMBERS . "§6]");
        $role = Faction::LEADER;
        $name = $faction->getName();
        $stmt = $this->getCore()->getMySQLProvider()->getDatabase()->prepare("SELECT username FROM players WHERE faction = ? and factionRole = ?");
        $stmt->bind_param("si", $name, $role);
        $stmt->execute();
        $stmt->bind_result($leader);
        $stmt->fetch();
        $stmt->close();
        $members = [];
        foreach($faction->getMembers() as $member) {
            if(($player = $this->getCore()->getServer()->getPlayer($member)) !== null) {
                $members[] = TextFormat::GREEN . $player->getName();
                continue;
            }
            $members[] = TextFormat::WHITE . $member;
        }
        $sender->sendMessage(" §6Leader: §7" . $leader);
        $sender->sendMessage(" §6Members: §7" . implode(TextFormat::GRAY . ", ", $members));
        $sender->sendMessage(" §6Allies: §f" . TextFormat::WHITE . implode(", ", $faction->getAllies()));
        $sender->sendMessage(" §6Power: §c" . $faction->getStrength() . " §6STR");
        $sender->sendMessage(" §6Balance: §7" . "§6$" . "§e" . number_format($faction->getBalance()));
    }
}