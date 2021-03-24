<?php

namespace core\item\types\raiding;

use core\item\CustomItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\TextFormat;

class TNTLauncher extends CustomItem {

    const TNTLauncher = "TNTLauncher";
    const TLUses = "TLUses";
    const TLTier = "TLTier";
    const TNTReq = "TNTReq";

    public function __construct(int $uses = 25, int $tier = 3) {

        //$amount = $tier * 2;
        $amount = 1;

        $customName = TextFormat::RESET . TextFormat::DARK_RED . TextFormat::BOLD . "TNT Launcher" . TextFormat::RESET;
        $lore = [];
        $lore[] = "";
        $lore[] = "§eThe greater the tier, the larger radius and tnt required.";
        $lore[] = "";
        $lore[] = "§r§cUses: §e$uses";
        $lore[] = "§r§cTier: §e$tier";
        $lore[] = "";
        $lore[] = "§r§7Each fire will require §e$amount §7TNT!";
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM));
        /** @var CompoundTag $tag */
        $tag = $this->getNamedTagEntry(self::CUSTOM);
        $tag->setInt(self::TLUses, $uses);
        $tag->setInt(self::TLTier, $tier);
        $tag->setInt(self::TNTReq, $amount);
        $tag->setString(self::TNTLauncher, "TNTLauncher");
        parent::__construct(self::WOODEN_HOE, $customName, $lore);
    }
}