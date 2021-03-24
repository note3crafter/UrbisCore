<?php

namespace core\cannons;

use core\cannons\entity\CannonEntity;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\plugin\Plugin;

class CannonManager{

    private $plugin;

    private static $skin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->registerListeners();
        Entity::registerEntity(CannonEntity::class, true, ["CannonEntityScarce"]);
    }

    /**
     * Registers Cannon Listeners
     */
    private function registerListeners(): void {
        $this->plugin->getServer()->getPluginManager()->registerEvents(new CannonListener(), $this->plugin);
        self::$skin = new Skin("Cannon", self::fromImage(imagecreatefrompng($this->plugin->getDataFolder() . "scarce_cannon.png")), "", "geometry.scarce_cannon",  file_get_contents($this->plugin->getDataFolder() . "scarce_cannon.geo.json"));
    }

    /**
     * @param $img
     * @return string
     * Returns bytes for Skin Usage
     */
    private static function fromImage($img)
    {
        $bytes = '';
        for ($y = 0; $y < imagesy($img); $y++) {
            for ($x = 0; $x < imagesx($img); $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

    public static function getSkin(): Skin{
        return self::$skin;
    }

}
