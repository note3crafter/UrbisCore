<?php

declare(strict_types = 1);

namespace core\item\enchantment;

abstract class Enchantment extends \pocketmine\item\enchantment\Enchantment {

    const DAMAGE = 0;

    const BREAK = 1;

    const EFFECT_ADD = 2;

    const MOVE = 3;

    const DEATH = 4;

    const SHOOT = 5;

    const INTERACT = 6;

    const DAMAGE_BY = 7;

    /** @var callable */
    protected $callable;

    /** @var string */
    private $description;

    /** @var int */
    private $eventType;

    /**
     * Armor Enchantments
     */

    const NOURISH = 100;

    const IMMUNITY = 101;

    const PERCEPTION = 102;

    const RESIST = 103;

    const BLESS = 104;

    const EVADE = 105;

    const OVERLOAD = 106;

    /**
     * Boots Enchantments
     */

    const GEARS = 130;

    const HOPS = 131;

    /**
     * Sword Enchantments
     */

    const WITHER = 160;

    const SHATTER = 161;

    const STUN = 162;

    const DRAIN = 163;

    const GUILLOTINE = 164;

    const MONOPOLIZE = 165;

    const BLEED = 166;

    const ANNIHILATION = 167;

    const FLING = 168;

    const SLAUGHTER = 169;
    
    const DOUBLESTRIKE = 170;

    const LIFESTEAL = 171;


    /**
     * Pickaxe Enchantments
     */

    const SMELTING = 190;

    const LUCK = 191;

    const HASTE = 192;

    const AMPLIFY = 193;

    const CHARM = 194;

    const JACKPOT = 195;

    const FORTUNE = 196;

    /**
     * Bow Enchantments
     */

    const VELOCITY = 220;

    const PARALYZE = 221;

    const PIERCING = 222;

    /**
     * Enchantment constructor.
     *
     * @param int $id
     * @param string $name
     * @param int $rarity
     * @param string $description
     * @param int $eventType
     * @param int $flag
     * @param int $maxLevel
     */
    public function __construct(int $id, string $name, int $rarity, string $description, int $eventType, int $flag, int $maxLevel) {
        $this->description = $description;
        $this->eventType = $eventType;
        parent::__construct($id, $name, $rarity, $flag, self::SLOT_NONE, $maxLevel);
    }

    /**
     * @return int
     */
    public function getEventType(): int {
        return $this->eventType;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable {
        return $this->callable;
    }
}
