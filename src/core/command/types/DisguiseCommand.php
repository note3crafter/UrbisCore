<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\CorePlayer;
use core\rank\Rank;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DisguiseCommand extends Command {

    /**
     * NickCommand constructor.
     */
    public function __construct() {
        parent::__construct("disguise", "Disguise as someone else");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if((!$sender instanceof CorePlayer) or ($sender->getRank()->getIdentifier() < 8 and !in_array($sender->getRank()->getIdentifier(), [Rank::MIERCENARY, Rank::YOUTUBER]))){
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
		}
		$disguiseList = ["DamexYama81", "CringeDotExe__", "yodaman412", "se_lina", "Vincenatorrr", "ohmyu", "Grelby", "belem7", "Mirshii", "SwxtchAddrezz", "iAmDisguised", "Nicked", "12vito12", "carnalito", "TurnaizyFanBoy", "iLoveTurnaizy", "_Laura_Craft_", "MiniCraftGamer93", "iLike14", "develhatverbot", "skellman912", "ErdamPE", "iAmaDam", "iLikePotatos", "WaitIsThatAPotato", "BaldBoyHalo", "TurnaizyFanboyME", "aastra", "Skeppy", "lolll121231312"];
		$randomiser = $disguiseList[mt_rand(0, count($disguiseList)-1)];
        $name = implode(" ", $args);
        if($name == "reset" or $name == "off"){
            $sender->setDisplayName($sender->getName());
            $sender->sendMessage("§l§6»§r §7You have successfully undisguised");
            return;
		}
		$sender->setDisplayName($randomiser);
        $sender->setDisplayName($randomiser);
        $sender->sendMessage("§l§a»§r §7You have been diguised as " . TextFormat::AQUA . $randomiser);
        return;
    }
}