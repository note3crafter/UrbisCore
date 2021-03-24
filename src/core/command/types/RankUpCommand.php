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

class RankUpCommand extends Command {

    /**
     * RankUpCommand constructor.
     */
    public function __construct() {
        parent::__construct("rankup", "Rank up", "/rankup", ["ru"]);
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
        $rank = $sender->getRank();
        switch($rank->getIdentifier()) {
            case Rank::ADVENTURER:
                $price = 1000000;
                $rankId = Rank::NOBLE;
                break;
            case Rank::NOBLE:
                $price = 1500000;
                $rankId = Rank::NOTRIX;
                break;
            case Rank::NOTRIX:
                $price = 3500000;
                $rankId = Rank::BARON;
                break;
            case Rank::BARON:
                $price = 7500000;
                $rankId = Rank::SPARTAN;
                break;
            case Rank::SPARTAN:
                $price = 20000000;
                $rankId = Rank::PRINCE;
                break;
            case Rank::PRINCE:
                $price = 50000000;
                $rankId = Rank::IMMORTAL;
                break;
            case Rank::IMMORTAL:
                $price = 1000000000;
                $rankId = Rank::CRYSTAL;
                break;
            default:
                if(!$sender->hasPermission("permission.starter")) {
                    $price = 1000000;
                    $permission = "permission.noble";
                }
                elseif(!$sender->hasPermission("permission.noble")) {
                    $price = 1500000;
                    $permission = "permission.notrix";
                }
                elseif(!$sender->hasPermission("permission.notrix")) {
                    $price = 3500000;
                    $permission = "permission.baron";
                }
                elseif(!$sender->hasPermission("permission.baron")) {
                    $price = 7500000;
                    $permission = "permission.spartan";
                }
                elseif(!$sender->hasPermission("permission.prince")) {
                    $price = 20000000;
                    $permission = "permission.immortal";
                }
                elseif(!$sender->hasPermission("permission.immortal")) {
                    $price = 1000000000;
                    $permission = "permission.crystal";
                }
                else {
                    $price = null;
                    $permission = null;
                }
        }
        if((!isset($price)) or $price === null) {
            $sender->sendMessage(Translation::getMessage("maxRank"));
            return;
        }
        if($price > $sender->getBalance()) {
            $sender->sendMessage(Translation::getMessage("notEnoughMoneyRankUp", [
                "amount" => TextFormat::RED . "$$price"
            ]));
            return;
        }
        if(isset($rankId)) {
            $sender->subtractFromBalance($price);
            $sender->setRank(($rank = $sender->getCore()->getRankManager()->getRankByIdentifier($rankId)));
            $this->getCore()->getServer()->broadcastMessage(Translation::getMessage("rankUp", [
                "name" => TextFormat::AQUA . $sender->getName(),
                "rank" => TextFormat::YELLOW . $rank->getName()
            ]));
        }
        elseif(isset($permission)) {
            $sender->subtractFromBalance($price);
            $sender->getSession()->addPermissions((string)$permission);
            $rank = ucfirst(explode(".", $permission)[1]);
            $this->getCore()->getServer()->broadcastMessage(Translation::getMessage("rankUp", [
                "name" => TextFormat::AQUA . $sender->getName(),
                "rank" => TextFormat::YELLOW . $rank
            ]));
        }
        else {
            $sender->sendMessage(Translation::getMessage("errorOccurred"));
        }
    }
}
