<?php

namespace core\entity\types;



use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Zombie extends Monster
{
    const NETWORK_ID = self::ZOMBIE;
    public $height = 1.98;
    public $width = 0.6;

    public function getDrops(): array
    {
        return [Item::get(Item::ROTTEN_FLESH,0,mt_rand(0,2)),Item::get(Item::POPPY,0,mt_rand(0,1))];
    }

    public function getName(): string
    {
        return "Zombie";
    }
}