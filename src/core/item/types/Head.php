<?php

declare(strict_types=1);

namespace core\item\types;

use core\item\CustomItem;
use core\UrbisPlayer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class Head extends CustomItem{

	const PLAYER = "Default";

	/**
	 * Head constructor.
	 *
	 * @param UrbisPlayer $player
	 */
	public function __construct(UrbisPlayer $player){
		$customName = "§b{$player->getName()}'s Head§r";
		$lore = [];
		$lore[] = "";
		$lore[] = "§7You will receive §b10%§r §7of §b{$player->getName()}§7’s money balance.§r";
		$lore[] = "";
		$lore[] = TextFormat::RESET . TextFormat::GRAY . "Go to headhunter in spawn to claim.";
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
		/** @var CompoundTag $tag */
		$tag = $this->getNamedTagEntry(self::CUSTOM);
		$tag->setString(self::PLAYER, $player->getXuid());
		$tag->setString("UniqueId", uniqid());
		$tag->setFloat("Balance", $player->getBalance() / 10);
		$tag->setString("Name", $player->getName());
		$player->subtractFromBalance($player->getBalance() / 10);
		parent::__construct(self::MOB_HEAD, $customName, $lore, [], [], 3);
	}
}