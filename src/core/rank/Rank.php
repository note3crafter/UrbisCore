<?php

declare(strict_types = 1);

namespace core\rank;

use core\Urbis;
use core\CorePlayer;

class Rank {

    const ADVENTURER = 0;

    const NOBLE = 1;

    const NOTRIX = 2;

    const BARON = 3;

    const SPARTAN = 4;

    const PRINCE = 5;

    const NITRO_BOOSTER = 6;

    const VULCAN = 7;

    const ZEUS = 8;

    const IMMORTAL = 9;

    const CRYSTAL = 10;

    const NEOPHYTE = 11;

    const CHRONUS = 12;
    
    const EMPEROR = 13;

    const TITAN = 14;

    const ENDERLORD = 15;

    const MERCENARY = 16;

    const TRAINEE = 17;

    const MODERATOR = 18;

    const SENIOR_MODERATOR = 19;

    const ADMIN = 20;

    const SENIOR_ADMIN = 21;

    const COOWNER = 22;

    const OWNER = 23;

    const YOUTUBER = 31;

    const GLORIOUS = 24;

    const FAMOUS = 25;
    
    const DEVELOPER = 26;
    
    const BUILDER = 27;
    
	const YOUTUBER_PLUS = 28;
    
    const MANAGER = 29;

    /** @var string */
    private $name;

    /** @var string */
    private $coloredName;

    /** @var int */
    private $identifier;

    /** @var string */
    private $chatFormat;

    /** @var string */
    private $tagFormat;

    /** @var array */
    private $permissions = [];

    /** @var int */
    private $homes;

    /** @var string */
    private $chatColor;

    /**
     * Rank constructor.
     *
     * @param string $name
     * @param string $chatColor
     * @param string $coloredName
     * @param int $identifier
     * @param string $chatFormat
     * @param string $tagFormat
     * @param int $homes
     * @param int $vaults
     * @param array $permissions
     */
    public function __construct(string $name, string $chatColor, string $coloredName, int $identifier, string $chatFormat, string $tagFormat, int $homes, int $vaults, array $permissions = []) {
        $this->name = $name;
        $this->chatColor = $chatColor;
        $this->coloredName = $coloredName;
        $this->identifier = $identifier;
        $this->chatFormat = $chatFormat;
        $this->tagFormat = $tagFormat;
        $this->homes = $homes;
        for($i = 1; $i <= $vaults; $i++) {
            $permissions = array_merge($permissions, ["playervaults.vault.$i"]);
        }
        $this->permissions = $permissions;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColoredName(): string {
        return $this->coloredName;
    }

    /**
     * @return string
     */
    public function getChatColor(): string {
        return $this->chatColor;
    }

    /**
     * @return int
     */
    public function getIdentifier(): int {
        return $this->identifier;
    }

    /**
     * @param CorePlayer $player
     * @param string        $message
     * @param array         $args
     *
     * @return string
     */
    public function getChatFormatFor(CorePlayer $player, string $message, array $args = []): string {
        $man = Urbis::getInstance()->getTagManager();
        $tag = $man->getTag($player, true);
		if($tag == null){
        	$tag = "";
        } else {
			$tag = $man->getTagFormat($player, $man->getTag($player, true));
		}
        $format = $this->chatFormat;
        foreach($args as $arg => $value) {
            $format = str_replace("{" . $arg . "}", $value, $format);
		}
		//$tag = $man->getTagFormat($player, $man->getTag($player, true) . " ");
        $format = str_replace("{player}", $player->getDisplayName(), $format);
        $format = str_replace("{tag}", $tag, $format);
        return str_replace("{message}", $message, $format);
    }

    /**
     * @param CorePlayer $player
     * @param array         $args
     *
     * @return string
     */
    public function getTagFormatFor(CorePlayer $player, array $args = []): string {
        $man = Urbis::getInstance()->getTagManager();
        $tag = $man->getTag($player, true);
        if($tag == null){
            $tag = "";
        } else {
			$tag = $man->getTagFormat($player, $man->getTag($player, true));
		}
        $format = $this->tagFormat;
        foreach($args as $arg => $value) {
            $format = str_replace("{" . $arg . "}", $value, $format);
		}
        $format = str_replace("{tag}", $tag, $format);
        return str_replace("{player}", $player->getName(), $format);
    }

    /**
     * @return string[]
     */
    public function getPermissions(): array {
        return $this->permissions;
    }

    /**
     * @return int
     */
    public function getHomeLimit(): int {
        return $this->homes;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
