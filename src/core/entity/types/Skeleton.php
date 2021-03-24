<?php

namespace core\entity\types;


use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Skeleton extends Monster
{
    public const NETWORK_ID = self::SKELETON;
    public $height = 1.99;
    public $width = 0.6;



    public function getDrops(): array
    {
        return [Item::get(Item::BONE,0,mt_rand(1,2))];
    }

    public function getName(): string
    {
        return "Sekeletal";
    }
}