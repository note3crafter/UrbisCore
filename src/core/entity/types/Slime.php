<?php

namespace core\entity\types;


use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Slime extends Monster
{
    const NETWORK_ID = self::SLIME;
    public $height = 1.02;
    public $width = 1.02;



    public function getDrops(): array
    {

        return [
            Item::get(Item::SLIME_BALL,0,mt_rand(1,2))
        ];
    }

    public function getName(): string
    {
        return "Slime";
    }
}