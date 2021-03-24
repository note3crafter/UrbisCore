<?php

declare(strict_types = 1);

namespace core\crate;

use core\CorePlayer;
use core\libs\form\CustomForm;
use core\libs\form\CustomFormResponse;
use core\libs\form\element\Label;
use core\libs\form\element\Slider;
use pocketmine\Player;

class CrateForm extends CustomForm {

    /** @var Crate */
    private $crate;

    /**
     * ShopForm constructor.
     * @param Crate $crate
     * @param CorePlayer $player
     */
    public function __construct(Crate $crate, CorePlayer $player) {
        $this->crate = $crate;

        $keys = $player->getKeys($crate);
        $emptySlots = $player->getInventory()->getSize() - count($player->getInventory()->getContents());
        $max = min($keys, $emptySlots);

        $elements = [
            new Label("header", "You have $keys {$crate->getName()} keys left"),
            new Label("header2", "$emptySlots empty inventory slots"),
            new Label("spacing", str_repeat("\n", 6))
        ];
        if($keys > 0) {
            $elements[] = new Slider("count", "How many crates You'd like to open", 1, $max, 1, 1);
        }

        parent::__construct($crate->getName()." Crate", $elements);
    }

    /**
     * @param Player $player
     * @param CustomFormResponse $response
     * @throws \core\translation\TranslationException
     */
    public function onSubmit(Player $player, CustomFormResponse $response): void {
        $data = $response->getAll();
        if(isset($data["count"])) {
            /** @var CorePlayer $player */
            $this->crate->try($player, (int) $data["count"]);
        }
    }

}