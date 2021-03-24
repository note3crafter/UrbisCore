<?php

declare(strict_types = 1);

namespace core\gamble\command\forms;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\FormIcon;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class CoinFlipListForm extends MenuForm {

    /**
     * CoinFlipListForm constructor.
     *
     * @param CorePlayer $player
     */
    public function __construct(CorePlayer $player) {
        $title = TextFormat::BOLD . TextFormat::YELLOW . "Coin Flip";
        $text = "Select a player to coin flip with.";
        $icon = new FormIcon("https://static.thenounproject.com/png/269428-200.png", FormIcon::IMAGE_TYPE_URL);
        $coinFlips = $player->getCore()->getGambleManager()->getCoinFlips();
        $options = [];
        $server = $player->getServer();
        foreach($coinFlips as $uuid => $coinFlip) {
            $p = $server->getPlayer($uuid);
            if($p !== null) {
                $options[] = new MenuOption($p->getName() . "\n" . TextFormat::RESET . TextFormat::BLACK . "$$coinFlip", $icon);
            }
        }
        parent::__construct($title, $text, $options);
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     *
     * @throws TranslationException
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        if(!$player instanceof CorePlayer) {
            return;
        }
        $name = explode("\n", $this->getOption($selectedOption)->getText())[0];
        $target = $player->getServer()->getPlayer($name);
        if(!$target instanceof CorePlayer) {
            $player->sendMessage(Translation::getMessage("invalidPlayer"));
            return;
        }
        if($target->getName() === $player->getName()) {
            $player->sendMessage(Translation::getMessage("invalidPlayer"));
            return;
        }
        $amount = $player->getCore()->getGambleManager()->getCoinFlip($target);
        if($target->getBalance() < $amount) {
            $player->sendMessage(Translation::getMessage("targetNotEnoughMoney", [
                "name" => TextFormat::RED . $target->getName()
            ]));
            return;
        }
        if($player->getBalance() < $amount) {
            $player->sendMessage(Translation::getMessage("notEnoughMoney"));
            return;
        }
        $player->sendForm(new CoinFlipConfirmationForm($target));
    }
}