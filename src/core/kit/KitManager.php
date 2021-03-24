<?php

declare(strict_types = 1);

namespace core\kit;

use core\kit\types\ConfigKit;
use core\kit\types\StarterKit;
use core\kit\types\WeeklyKit;
use core\kit\types\MonthlyKit;
use core\kit\types\OnceKit;
use core\kit\types\NobleKit;
use core\kit\types\NotrixKit;
use core\kit\types\BaronKit;
use core\kit\types\SpartanKit;
use core\kit\types\PrinceKit;
use core\kit\types\ImmortalKit;
use core\kit\types\CrystalKit;
use core\kit\types\VulcanKit;
use core\kit\types\NeophyteKit;
use core\kit\types\ChronusKit;
use core\kit\types\ZeusKit;
use core\kit\types\EmperorKit;
use core\kit\types\TitanKit;
use core\kit\types\EnderlordKit;
use core\kit\types\MercenaryKit;
use core\kit\types\GloriousKit;
use core\kit\types\MinerGKit;
use core\kit\types\ArgusGKit;
use core\kit\types\AtlasGKit;
use core\kit\types\AgaraGKit;
use core\kit\types\ReaperGKit;
use core\Urbis;

class KitManager {

    /** @var Urbis */
	private $core;
	/** @var Kit[] */
	private $kits = [];
	/** @var Kit[] */
	private $godlyKits = [];
	/** @var array */
	private $data = [];

    /**
     * KitManager constructor.
     *
     * @param Urbis $core
     *
     * @throws KitException
     */
	public function __construct(Urbis $core) {
		$this->core = $core;
        if(file_exists($this->getCooldownPath() . DIRECTORY_SEPARATOR . "cooldown.json")){
            $this->data = json_decode(file_get_contents($this->getCooldownPath() . DIRECTORY_SEPARATOR . "cooldown.json"), true);
        }
		$this->init();
	}

    /**
     * @throws KitException
     */
	public function init(): void {
        $this->addKit(new StarterKit());
        $this->addKit(new WeeklyKit());
        $this->addKit(new MonthlyKit());
        $this->addKit(new OnceKit());
        $this->addKit(new NobleKit());
        $this->addKit(new NotrixKit());
        $this->addKit(new BaronKit());
        $this->addKit(new SpartanKit());
        $this->addKit(new PrinceKit());
        $this->addKit(new ImmortalKit());
        $this->addKit(new CrystalKit());
        $this->addKit(new VulcanKit());
        $this->addKit(new NeophyteKit());
        $this->addKit(new ChronusKit());
        $this->addKit(new ZeusKit());
        $this->addKit(new EmperorKit());
        $this->addKit(new TitanKit());
        $this->addKit(new EnderlordKit());
        $this->addKit(new MercenaryKit());
        $this->addKit(new GloriousKit());
        $this->addKit(new MinerGKit());
        $this->addKit(new ArgusGKit());
        $this->addKit(new AtlasGKit());
        $this->addKit(new ReaperGKit());
        //$this->addKit(new AgaraGKit());
	}

    /**
     * @param string $kit
     *
     * @return Kit|null
     */
	public function getKitByName(string $kit) : ?Kit {
		return $this->kits[$kit] ?? null;
	}

    /**
     * @return Kit[]
     */
	public function getKits(): array {
	    return $this->kits;
    }

    /**
     * @return Kit[]
     */
    public function getGodlyKits(): array {
        return $this->godlyKits;
    }

	/**
	 * @param Kit $kit
	 *
	 * @throws KitException
	 */
	public function addKit(Kit $kit) : void {
		if(isset($this->kits[$kit->getName()])) {
			throw new KitException("Attempted to override a kit with the name of \"{$kit->getName()}\" and a class of \"" . get_class($kit) . "\".");
		}
		$this->kits[$kit->getName()] = $kit;
		if($kit->getRarity() > Kit::UNCOMMON) {
		    $this->godlyKits[] = $kit;
        }
	}

    /**
     * @param string $kit
     * @param string $player
     * @return int
     */
	public function getCooldown(string $kit, string $player): int{
	    return isset($this->data[$kit][$player]) ? $this->data[$kit][$player] : 0;
    }

    /**
     * @param string $kit
     * @param string $player
     * @param int $time
     */
    public function addToCooldown(string $kit, string $player, int $time): void{
        $this->data[$kit][$player] = time();
        $this->save();
    }

    /**
     * @param string $kit
     * @param string $player
     */
    public function removeFromCooldown(string $kit, string $player): void{
	    if(isset($this->data[$kit][$player]))
        unset($this->data[$kit][$player]);
    }

    /**
     * @return string
     */
     public function getCooldownPath(): string{
		    return $this->core->getInstance()->getDataFolder() . "kits";

    }
 
	
    public function save(): void{
        file_put_contents($this->getCooldownPath() . DIRECTORY_SEPARATOR . "cooldown.json", json_encode($this->data, JSON_PRETTY_PRINT));
    }
}
