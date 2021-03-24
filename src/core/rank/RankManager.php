<?php

declare(strict_types = 1);

namespace core\rank;

use core\Urbis;
use pocketmine\utils\TextFormat;

class RankManager {

    /** @var Urbis */
    private $core;

    /** @var Rank[] */
    private $ranks = [];

    /**
     * RankManager constructor.
     *
     * @param Urbis $core
     *
     * @throws RankException
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new RankListener($core), $core);
        $this->init();
    }

    /**
     * @throws RankException
     */
    public function init(): void {
    $this->addRank(new Rank("Adventurer", TextFormat::RESET, TextFormat::RESET . "§l<§r§7Adventurer§l§f>§r", Rank::ADVENTURER, // DEFAULT RANK
        "⚔ §f§8(§c{faction_rank}§3{faction}§8)§r §r§7{player}§7 {tag}§8§l: §r§f{message}",
        "§e[{faction}]§r\n§7Adventurer §r§f{player}", 5, 1, [
            "permission.starter",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Noble", TextFormat::RESET, TextFormat::RESET . "§l§4<§r§cNoble§l§4>§r", Rank::NOBLE, // FREE RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§cNOBLE§r §r§7{player}§7 {tag}§8§l: §r§c{message}",
        "§e[{faction}]§r\n §cNOBLE§r §r§7{player}", 7, 2,  [
            "permission.starter",
            "permission.noble",
            "playervaults.vault.noble",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Notrix", TextFormat::RESET, TextFormat::RESET . "§l<§r§bNotrix§l§f>§r", Rank::NOTRIX, // FREE RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§bNOTRIX§r §r§7{player}§7 {tag}§8§l: §r§b{message}",
        "§e[{faction}]§r\n §l§bNOTRIX§r §r§7{player}", 7, 2,  [
            "permission.starter",
            "permission.noble",
            "playervaults.vault.notrix",
            "permission.notrix",
            "permission.tier2",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Baron", TextFormat::RESET, TextFormat::RESET . "§l§e<§r§6Baron§l§e>§r", Rank::BARON, // FREE RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§6BARON§r §r§7{player}§7 {tag}§8§l: §r§6{message}",
        "§e[{faction}]§r\n §l§6BARON§r §r§7{player}", 7, 2,  [
            "permission.starter",
            "permission.noble",
            "permission.notrix",
            "permission.baron",
            "playervaults.vault.notrix",
            "permission.tier2",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Spartan", TextFormat::RESET, TextFormat::RESET . "§l§6<§r§eSpartan§l§6>§r", Rank::SPARTAN, // FREE RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§eSPARTAN§r §r§7{player}§7 {tag}§8§l: §r§e{message}",
        "§e[{faction}]§r\n §l§eSPARTAN§r §r§7{player}", 7, 2,  [
            "permission.starter",
            "permission.noble",
            "permission.notrix",
            "permission.baron",
            "permission.spartan",
            "playervaults.vault.notrix",
            "permission.tier2",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Prince", TextFormat::RESET, TextFormat::RESET . "§l§5<§r§dPrince§l§5>§r", Rank::PRINCE, // FREE RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§dPRINCE§r §r§7{player}§7 {tag}§8§l: §r§d{message}",
        "§e[{faction}]§r\n §l§dPRINCE§r §r§7{player}", 11, 4, [
            "permission.starter",
            "permission.noble",
            "permission.notrix",
            "permission.baron",
            "permission.spartan",
            "permission.prince",
            "playervaults.vault.prince",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Immortal", TextFormat::RESET, TextFormat::RESET . "§l§3<§r§bImmortal§l§3>§r", Rank::IMMORTAL, // FREE RANK
        "§8(§c{faction_rank}§3{faction}§8)§r §l§bIMMORTAL§r §r§7{player}§7{tag}§8§l: §r§b{message}",
        "§e[{faction}]§r\n §r§l§bIMMORTAL§r §r§7{player}", 11, 4, [
            "permission.starter",
            "permission.noble",
            "permission.notrix",
            "permission.baron",
            "permission.spartan",
            "permission.prince",
            "permission.immortal",
            "playervaults.vault.prince",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
        $this->addRank(new Rank("Crystal", TextFormat::RESET, TextFormat::RESET . "§l<§r§dCrystal§l§f>§r", Rank::CRYSTAL, // FREE RANK
        "§8(§c{faction_rank}§3{faction}§8)§r §l§dCRYSTAL§r §r§7{player}§7 {tag} §8§l» §r§d{message}",
        "§e[{faction}]§r\n §l§dCRYSTAL§r §r§7{player}", 11, 4, [
            "permission.starter",
            "permission.noble",
            "permission.notrix",
            "permission.baron",
            "permission.spartan",
            "permission.prince",
            "permission.immortal",
            "permission.crystal",
            "playervaults.vault.prince",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Nitro-Booster", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§dNitro-Booster§l§f>§r", Rank::NITRO_BOOSTER, // DISCORD BOOST RANK
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§dNITRO BOOSTER§r §r§7{player}§7 {tag} §8§l» §r§d{message}",
        "§e[{faction}]§r\n §l§dNITRO BOOSTER§r §r§7{player}", 11, 4, [
            "permission.starter",
            "permission.vulcan",
            "playervaults.vault.prince",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Vulcan", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§4Vul§6can§l§f>§r", Rank::VULCAN,
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§4VUL§eCAN§r §r§7{player}§7 {tag} §8§l» §r§6{message}",
        "§e[{faction}]§r\n §l§4VUL§6CAN§r §r§7{player}", 13, 5, [
            "permission.starter",
            "permission.vulcan",
            "playervaults.vault.prince",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Neophyte", TextFormat::RESET, TextFormat::RESET . "§l§2<§r§aNeophyte§l§2>§r", Rank::NEOPHYTE, 
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§aNEOPHYTE§r §r§7{player}§7 {tag} §8§l» §r§a{message}",
        "§e[{faction}]§r\n §l§aNEOPHYTE§r §r§7{player}", 13, 5, [
            "permission.starter",
            "permission.vulcan",
            "permission.chronus",
            "permission.neophyte",
            "permission.anarchist",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly"
        ]));
    $this->addRank(new Rank("Chronus", TextFormat::RESET, TextFormat::RESET . "§l§3<§r§9Chronus§l§3>§r", Rank::CHRONUS, 
        "⚔ §8(§c{faction_rank}§3{faction}§8)§r §l§9CHRONUS§r §r§7{player}§7 {tag} §8§l» §r§9{message}",
        "§e[{faction}]§r\n §l§9CHRONUS§r §r§7{player}", 15, 6, [
            "permission.starter",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Zeus", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§3Zeus§l§f>§r", Rank::ZEUS, 
        "§8(§c{faction_rank}§3{faction}§8)§r §l§3ZEUS§r §r§7{player}§7 {tag} §8§l» §r§3{message}",
        "§e[{faction}]§r\n §l§3ZEUS§r §r§7{player}", 15, 6, [
            "permission.starter",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Emperor", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§9Emperor§l§f>§r", Rank::EMPEROR, 
        "§8(§c{faction_rank}§3{faction}§8)§r §l§9EMPEROR§r §r§7{player}§7 {tag} §8§l» §r§9{message}",
        "§e[{faction}]§r\n §l§9EMPEROR§r §r§7{player}", 15, 6, [
            "permission.starter",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.tier1",
            "permission.tier2",
			"permission.tier3",
            "permission.once",
            "permission.fly",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Titan", TextFormat::RESET, TextFormat::RESET . "§l§4<§r§cTitan§l§4>§r", Rank::TITAN,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§cTITAN§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §c§lTITAN§r §r§7{player}", 15, 8, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Enderlord", TextFormat::RESET, TextFormat::RESET . "§l§d<§r§5Enderlord§l§d>§r", Rank::ENDERLORD,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§5ENDERLORD§r §r§7{player}§7 {tag} §8§l» §r§5{message}",
        "§e[{faction}]§r\n §5§lENDERLORD§r §r§e{player}", 18, 10, [
            "permission.starter",
            "permission.tier1",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.fly",
            "permission.feed",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Mercenary", TextFormat::RESET, TextFormat::RESET . "§l§c<§r§4Mercenary§l§c>§r", Rank::MERCENARY,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§4MERCENARY§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §4§lMERCENARY§r §r§c{player}", 25, 15, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.enderlord",
            "permission.titan",
            "permission.mercenary",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.join.full",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Glorious", TextFormat::RESET, TextFormat::RESET . "§l§5<§fGlor§dious§l§5>§r", Rank::GLORIOUS,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§fGLOR§dIOUS§r §r§7{player}§7 {tag} §8§l» §r§d{message}",
        "§e[{faction}]§r\n §f§lGLOR§dIOUS§r §r§d{player}", 25, 15, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.once",
            "permission.join.full",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Trainee", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§eTrainee§l§f>§r", Rank::TRAINEE,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§eTRAINEE§r §r§7{player}§7 {tag} §8§l» §r§e{message}",
        "§e[{faction}]§r\n §l§eTRAINEE§r §r§7{player}", 20, 10, [
           "permission.starter",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.staff",
            "permission.join.full",
            "playervaults.others.view",
            "stafftable.ui", // STAFFTABLE
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", //BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Mod", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§cMod§l§f>§r", Rank::MODERATOR,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§cMODERATOR§r §r§7{player} {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§cMOD§r §r§7{player}", 25, 15, [
            "permission.starter",
            "permission.anarchist",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "playervaults.others.view",
            "permission.avatar",
            "permission.join.full",
            "permission.staff",
            "stafftable.ui", //STAFFTABLE
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", //BANSYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Senior-Mod", TextFormat::RESET, TextFormat::RESET . "§l§f<§r§2Senior§f-§aMod§l§f>§r", Rank::SENIOR_MODERATOR,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§2Senior-§aMod§r §r§7{player} {tag} §8§l» §r§a{message}",
        "§e[{faction}]§r\n §l§2Senior §aModerator§r §r§7{player}", 30, 20, [
            "permission.starter",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.youtuber",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "staff.messages",
            "permission.chronus",
            "permission.titan",
            "permission.anarchist",
            "playervaults.others.view",
            "stafftable.form",//STAFFTABLE
            "stafftable.command",
            "bansystem.command.tempban", //BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Admin", TextFormat::RESET, TextFormat::RESET . "§l§c<§r§4Administrator§l§c>§r", Rank::ADMIN,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§4ADMINISTRATOR§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§4ADMINISTRATOR§r §r§7{player}", 35, 25, [
            "permission.starter",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "playervaults.others.view",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "stafftable.ui",//STAFFTABLE
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.sacredall",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Senior-Admin", TextFormat::RESET, TextFormat::RESET . "§l§c<§r§4Senior§f-§cAdmin§l§c>§r", Rank::SENIOR_ADMIN,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§4SENIOR§f-§cADMIN§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§4SENIOR§f-§cADMIN§r §r§7{player}", 40, 30, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "playervaults.others.view",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "pocketmine.command.gamemode",
             "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "bansystem.command.mutelist",
            "bansystem.command.banlist",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
     $this->addRank(new Rank("Manager", TextFormat::RESET, TextFormat::RESET . "§l§3<§r§9Manager§l§3>§r", Rank::MANAGER,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§9MANAGER§r §r§7{player}§7 {tag} §8§l» §r§9{message}",
        "§e[{faction}]§r\n §l§9MANAGER§r §r§7{player}", 40, 30, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "playervaults.others.view",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "pocketmine.command.teleport",
            "pocketmine.command.gamemode",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "bansystem.command.mutelist",
            "bansystem.command.banlist",
            "permission.once",
            "permission.sacredall",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ] ));
    $this->addRank(new Rank("Co-Owner", TextFormat::RESET, TextFormat::RESET . "§l§c<§r§fCo-§4Owner§l§c>§r", Rank::COOWNER,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§fCO-§4OWNER§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§fCO-§4OWNER§r §r§7{player}", 45, 35, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.youtuber",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "playervaults.others.view",
            "playervaults.others.edit",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "pocketmine.command.teleport",
            "pocketmine.command.gamemode",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "bansystem.command.mutelist",
            "bansystem.command.banlist",
            "bansystem.command.banip",
            "bansystem.command.ban",
            "bansystem.command.mute",
            "permission.once",
            "permission.sacredall",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Owner", TextFormat::RESET, TextFormat::RESET . "§l§c<§r§4Owner§l§c>§r", Rank::OWNER,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§4OWNER§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§4OWNER§r §r§c{player}", 50, 40, [
            "permission.starter",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "playervaults.others.view",
            "permission.staff",
            "pocketmine.command.teleport",
            "pocketmine.command.gamemode",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "bansystem.command.mutelist",
            "bansystem.command.banlist",
            "bansystem.command.banip",
            "bansystem.command.ban",
            "bansystem.command.mute",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("YouTube", TextFormat::RESET, TextFormat::RESET . "§l§f<§rYou§cTube§l§f>§r", Rank::YOUTUBER,
        "§8(§c{faction_rank}§3{faction}§8)§r §l§fYou§cTube§r §r§7{player}§7 {tag} §8§l» §r§c{message}",
        "§e[{faction}]§r\n §l§fYou§cTube§r §r§7{player}", 20, 10, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "permission.join.full",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Famous", TextFormat::RESET, TextFormat::RESET . "§l§5<§r§dFamous§l§5>§r", Rank::FAMOUS,
        "§8(§c{faction_rank}§3{faction}§8) §l§dFAMOUS§r §r§7{player}§7 {tag} §8§l» §r§d{message}",
        "§e[{faction}]§r\n §l§dFAMOUS§r §r§7{player}", 25, 15, [
            "permission.starter",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.avatar",
            "permission.tier2",
            "permission.tier3",
            "permission.join.full",
            "permission.feed",
            "permission.once",
            "permission.monthly",
            "permission.weekly"
            ]));
   $this->addRank(new Rank("Developer", TextFormat::RESET, TextFormat::RESET . "§l§5<§r§dDeveloper§l§5>§r", Rank::DEVELOPER,
        "§8(§c{faction_rank}§3{faction}§8) §l§dDEVELOPER§r §r§7{player}§7 {tag} §8§l» §r§d{message}",
        "§e[{faction}]§r\n §l§dDEVELOPER§r §7{player}", 35, 30, [
            "permission.starter",
            "permission.feed",
            "permission.fly",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "playervaults.others.view",
            "permission.join.full",
            "permission.staff",
            "pocketmine.command.teleport",
            "pocketmine.command.gamemode",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "bansystem.command.mutelist",
            "bansystem.command.banlist",
            "bansystem.command.banip",
            "bansystem.command.ban",
            "bansystem.command.mute",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
    $this->addRank(new Rank("Builder", TextFormat::RESET, TextFormat::RESET . "§l§f<§6Builder§l§f>§r", Rank::BUILDER,
        "§8(§c{faction_rank}§3{faction}§8) §l§6BUILDER§r §r§7{player}§7 {tag} §8§l» §r§6{message}",
        "§e[{faction}]§r\n §l§6BUILDER§r §r§7{player}", 45, 35, [
            "permission.starter",
            "permission.titan",
            "permission.vulcan",
            "permission.neophyte",
            "permission.chronus",
            "permission.zeus",
            "permission.emperor",
            "permission.titan",
            "permission.enderlord",
            "permission.mercenary",
            "permission.glorious",
            "permission.tier1",
            "permission.tier2",
            "permission.tier3",
            "permission.mod",
            "permission.join.full",
            "permission.staff",
            "playervaults.others.view",
            "stafftable.ui",
            "staff.messages",
            "stafftable.form",
            "stafftable.command",
            "bansystem.command.tempban", // BAN SYSTEM
            "bansystem.command.tempbanip",
            "bansystem.command.unban",
            "bansystem.command.unbanip",
            "bansystem.command.tempmute",
            "bansystem.command.tempmuteip",
            "bansystem.command.unmute",
            "bansystem.command.unmuteip",
            "bansystem.command.kick",
            "bansystem.command.pardon",
            "permission.once",
            "permission.once",
            "invsee",
            "permission.monthly",
            "permission.weekly"
        ]));
   
}

    /**
     * @param int $identifier
     *
     * @return Rank|null
     */
    public function getRankByIdentifier(int $identifier): ?Rank {
        return $this->ranks[$identifier] ?? null;
    }

    /**
     * @return Rank[]
     */
    public function getRanks(): array {
        return $this->ranks;
    }

    /**
     * @param string $name
     *
     * @return Rank
     */
    public function getRankByName(string $name): ?Rank {
        return $this->ranks[$name] ?? null;
    }

    /**
     * @param Rank $rank
     *
     * @throws RankException
     */
    public function addRank(Rank $rank): void {
        if(isset($this->ranks[$rank->getIdentifier()]) or isset($this->ranks[$rank->getName()])) {
            throw new RankException("Attempted to override a rank with the identifier of \"{$rank->getIdentifier()}\" and a name of \"{$rank->getName()}\".");
        }
        $this->ranks[$rank->getIdentifier()] = $rank;
        $this->ranks[$rank->getName()] = $rank;
    }
}