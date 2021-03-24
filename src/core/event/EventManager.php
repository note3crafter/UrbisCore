<?php

declare(strict_types = 1);

namespace core\event;

use core\event\misc\CharmReward;
use core\event\relics\CommonRewardChooser;
use core\event\relics\RareRewardChooser;
use core\event\relics\MythicRewardChooser;
use core\event\relics\LegendaryChooser;
use core\event\misc\BossReward;
use core\event\misc\RankReward;
use core\event\misc\LootBagReward;
use core\Urbis;

class EventManager {
	/**
	 * @var LegendaryChooser
	 */
	private static $legendaryChooser;

	/**
	 * @var MythicRewardChooser
	 */
	private static $mythicChooser;

	/**
	 * @var RareRewardChooser
	 */
	private static $rareChooser;

	/**
	 * @var CommonRewardChooser
	 */
	private static $commonChooser;

	/**
	 * @var BossReward
	 */
	private static $bossChooser;

	/**
	 * @var CharmReward
	 */
	private static $charmChooser;

	/**
	 * @var RankReward
	 */
	private static $rankChooser;

	/**
	 * @var LootBagReward
	 */
	private static $lootChooser;

	/** @var Urbis */
    private $core;

    /**
     * ItemManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
		self::$legendaryChooser = new LegendaryChooser();
		self::$mythicChooser = new MythicRewardChooser();
		self::$rareChooser = new RareRewardChooser();
		self::$commonChooser = new CommonRewardChooser();
		self::$charmChooser = new CharmReward();
		self::$bossChooser = new BossReward();
		self::$rankChooser = new RankReward();
		self::$lootChooser = new LootBagReward();
    }

	/**
	 * @return LegendaryChooser
	 */
	public static function getLegendaryChooser(): LegendaryChooser {
		return self::$legendaryChooser;
	}
	/**
	 * @return MythicRewardChooser
	 */
	public static function getMythicChooser(): MythicRewardChooser {
		return self::$mythicChooser;
	}
	/**
	 * @return RareRewardChooser
	 */
	public static function getRareChooser(): RareRewardChooser {
		return self::$rareChooser;
	}
	/**
	 * @return CommonRewardChooser
	 */
	public static function getCommonChooser(): CommonRewardChooser {
		return self::$commonChooser;
	}
	/**
	 * @return CharmReward
	 */
	public static function getCharmChooser(): CharmReward {
		return self::$charmChooser;
	}
	/**
	 * @return BossReward
	 */
	public static function getBossChooser(): BossReward {
		return self::$bossChooser;
	}
	/**
	 * @return RankReward
	 */
	public static function getRankChooser(): RankReward {
		return self::$rankChooser;
	}
	/**
	 * @return LootBagReward
	 */
	public static function getLootBagChooser(): LootBagReward {
		return self::$lootChooser;
	}
}