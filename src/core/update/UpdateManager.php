<?php

declare(strict_types = 1);

namespace core\update;

use core\Urbis;
use core\CorePlayer;
use core\update\task\UpdateTask;
use core\utils\UtilsException;
use pocketmine\item\Armor;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class UpdateManager {

    /** @var Urbis */
    private $core;

    /**
     * UpdateManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $core->getScheduler()->scheduleRepeatingTask(new UpdateTask($core), 1);
    }

    /**
     * @param CorePlayer $player
     *
     * @throws UtilsException
     */
    public function updateScoreboard(CorePlayer $player): void {
        $scoreboard = $player->getScoreboard();
        if($scoreboard === null) {
            return;
        }
        if($scoreboard->isSpawned() === false) {
            $scoreboard->spawn($this->core->scoreBoardTitle);
            return;
        }

        if (!$player->isUsingPVPHUD()) {
            if (count($scoreboard->getLines()) > count($this->core->pvpScoreBoardLines)) {
                $scoreboard->removeLine(array_key_last($this->core->pvpScoreBoardLines));
            }
            foreach ($this->core->pvpScoreBoardLines as $i => $line) {
                $scoreboard->setScoreLine($i+1, TextFormat::colorize($this->getTagValue($line, $player)));
            }
        } else {
            if (count($scoreboard->getLines()) > count($this->core->scoreBoardLines)) {
                $scoreboard->removeLine(array_key_last($this->core->scoreBoardLines));
            }
            foreach ($this->core->scoreBoardLines as $i => $line) {
                $scoreboard->setScoreLine($i+1, TextFormat::colorize($this->getTagValue($line, $player)));
            }
        }
    }

    private function getTagValue(string $line, CorePlayer $player): string {

        $helmet = $player->getArmorInventory()->getHelmet();
        $chestplate = $player->getArmorInventory()->getChestplate();
        $leggings = $player->getArmorInventory()->getLeggings();
        $boots = $player->getArmorInventory()->getBoots();
        $d = $player->getFaction() == null ? "N/A" : $player->getFaction()->getStrength();
        $precision = 1;
		if ($d >= 0 && $d < 1000) {
			$n_format = floor($d);
			$suffix = '';
		} else if ($d < 900000) {
			// 0.9k-850k
			$n_format = number_format($d / 1000, $precision);
			$suffix = 'K';
		} else if ($d < 900000000) {
			// 0.9m-850m
			$n_format = number_format($d / 1000000, $precision);
			$suffix = 'M';
		} else if ($f < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($d / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($d / 1000000000000, $precision);
			$suffix = 'T';
		}
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
		$shortFactionPower = $n_format.$suffix;
        $f = $player->getFaction() == null ? "N/A" : $player->getFaction()->getBalance();
        $precision = 1;
		if ($f >= 0 && $f < 1000) {
			$n_format = floor($f);
			$suffix = '';
		} else if ($f < 900000) {
			// 0.9k-850k
			$n_format = number_format($f / 1000, $precision);
			$suffix = 'K';
		} else if ($f < 900000000) {
			// 0.9m-850m
			$n_format = number_format($f / 1000000, $precision);
			$suffix = 'M';
		} else if ($f < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($f / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($f / 1000000000000, $precision);
			$suffix = 'T';
		}
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
		$shortFactionMoney = $n_format.$suffix;
        $n = $player->getBalance();
        $precision = 1;
		if ($n >= 0 && $n < 1000) {
			$n_format = floor($n);
			$suffix = '';
		} else if ($n < 900000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			// 0.9m-850m
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
        $shortMoney = $n_format.$suffix;
        $tags = [
            "{rank}" => $player->getRank()->getColoredName(),
            "{player}" => $player->getName(),
            "{faction}" => $player->getFaction() == null ? "N/A" : $player->getFaction()->getName(),
            "{faction_money}" => $shortFactionMoney,
			"{faction_power}" => $shortFactionPower,
			"{faction_noformatbal}" => $player->getFaction() == null ? "N/A" : $player->getFaction()->getBalance(),
			"{faction_online}" => $player->getFaction() == null ? "0" : count($player->getFaction()->getMembers()),
            "{kills}" => $player->getKills(),
            "{xp}" => $player->getXpLevel(),
            "{ping}" =>  $player->getPing(),
            "{online}" => count($player->getServer()->getOnlinePlayers()),
            "{max_online}" => $player->getServer()->getMaxPlayers(),
			"{bal}" => $shortMoney,
			"{noformatbal}" => $player->getBalance(),
			"{number_bal}" => number_format($player->getBalance()),
			"{shards}" => number_format($player->getShards()),
			"{mob_coins}" => number_format($player->getMobCoins()),
            "{helmet}" => $helmet instanceof Armor ? $helmet->getMaxDurability() - $helmet->getDamage() : "None.",
            "{chestplate}" => $chestplate instanceof Armor ? $chestplate->getMaxDurability() - $chestplate->getDamage() : "None",
            "{leggings}" => $leggings instanceof Armor ? $leggings->getMaxDurability() - $leggings->getDamage() : "None",
            "{boots}" => $boots instanceof Armor ? $boots->getMaxDurability() - $boots->getDamage() : "None",
            "{gap_cooldown}" => $this->core->getCombatManager()->getGoldenAppleCooldown($player),
            "{egap_cooldown}" => $this->core->getCombatManager()->getGodAppleCooldown($player),
            "{e_cooldown}" => $this->core->getCombatManager()->getEnderPearlCooldown($player)
        ];

        $string = $line;
        foreach ($tags as $tag => $value) {
            $string = str_replace($tag, $value, $string);
        }

        return $string;
    }
}
