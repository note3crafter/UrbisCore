<?php

declare(strict_types = 1);

namespace core\crate;

use core\crate\types\RareCrate;
use core\crate\types\EpicCrate;
use core\crate\types\LegendaryCrate;
use core\crate\types\MythicCrate;
use core\crate\types\CommonCrate;
use core\crate\types\VoteCrate;
use core\Urbis;
use core\CorePlayer;
use core\item\ItemManager;
use core\item\types\EnchantmentBook;
use core\item\types\XPNote;
use core\kit\Kit;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\utils\Config;

class CrateManager {

    /** @var Urbis */
    private $core;

    /** @var Crate[] */
    private $crates = [];

    /** @var MonthlyCrate */
    private $monthlyCrate;

    /**
     * CrateManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new CrateListener($core), $core);
        $this->init();
    }

    public function init() {

        $datafiles = ["common.json", "legendary.json", "mythic.json", "epic.json", "rare.json", "vote.json", "monthly.json"];
        $data = [];

        foreach ($datafiles as $file) {
            $data[pathinfo($file, PATHINFO_FILENAME)] = new Config($this->core->getDataFolder()."crates/$file");
        }

        $X = $data["common"]->getNested("position.x");
        $Y = $data["common"]->getNested("position.y");
        $Z = $data["common"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["common"]->getNested("position.level"));
        $this->addCrate(new CommonCrate(new Position($X, $Y, $Z, $level)));

        $X = $data["legendary"]->getNested("position.x");
        $Y = $data["legendary"]->getNested("position.y");
        $Z = $data["legendary"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["legendary"]->getNested("position.level"));
        $this->addCrate(new LegendaryCrate(new Position($X, $Y, $Z, $level)));

        $X = $data["mythic"]->getNested("position.x");
        $Y = $data["mythic"]->getNested("position.y");
        $Z = $data["mythic"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["mythic"]->getNested("position.level"));
        $this->addCrate(new MythicCrate(new Position($X, $Y, $Z, $level)));

        $X = $data["epic"]->getNested("position.x");
        $Y = $data["epic"]->getNested("position.y");
        $Z = $data["epic"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["epic"]->getNested("position.level"));
        $this->addCrate(new EpicCrate(new Position($X, $Y, $Z, $level)));

        $X = $data["rare"]->getNested("position.x");
        $Y = $data["rare"]->getNested("position.y");
        $Z = $data["rare"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["rare"]->getNested("position.level"));
        $this->addCrate(new RareCrate(new Position($X, $Y, $Z, $level)));

        $X = $data["vote"]->getNested("position.x");
        $Y = $data["vote"]->getNested("position.y");
        $Z = $data["vote"]->getNested("position.z");
        $level = $this->core->getServer()->getLevelByName($data["vote"]->getNested("position.level"));
        $this->addCrate(new VoteCrate(new Position($X, $Y, $Z, $level)));

    }

    /**
     * @return Crate[]
     */
    public function getCrates(): array {
        return $this->crates;
    }

    /**
     * @param string $identifier
     *
     * @return Crate|null
     */
    public function getCrate(string $identifier): ?Crate {
        return isset($this->crates[strtolower($identifier)]) ? $this->crates[strtolower($identifier)] : null;
    }

    /**
     * @param Crate $crate
     */
    public function addCrate(Crate $crate) {
        $this->crates[strtolower($crate->getName())] = $crate;
    }
}