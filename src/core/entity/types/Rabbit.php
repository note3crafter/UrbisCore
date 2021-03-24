<?php

namespace core\entity\types;



use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Rabbit extends Animal
{
    public const NETWORK_ID = self::RABBIT;
    public $height =0.5;
    public $width = 0.4;

    public function getDrops(): array
    {
     if (mt_rand(0, 100) <= 89) {
            $drops[] = Item::get(Item::APPLE,0,mt_rand(1,2));
        }
     if (mt_rand(0, 100) <= 99) {
            $drops[] = Item::get(Item::RABBIT_FOOT,0,mt_rand(1,2));
        }
        return $drops;
    }

    public function getName(): string
    {
        return "Rabbit";
    }
}
