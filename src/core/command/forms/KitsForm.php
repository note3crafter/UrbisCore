<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\item\types\ChestKit;
use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use pocketmine\Player;

class KitsForm extends MenuForm {

    /**
     * KitsForm constructor.
     * @param array $kits
     */
    public function __construct(array $kits) {
        $title = "§l§cKits§r";
        $text = "Select a kit.";
        $options = [];
        foreach($kits as $kit) {
            $options[] = new MenuOption($kit->getName());
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
        $time = time();
        $kitManager = Urbis::getInstance()->getKitManager();
        $name = explode("\n", $this->getOption($selectedOption)->getText())[0];
        $kit = $kitManager->getKitByName($name);
        $item = (new ChestKit($kitManager->getKitByName($name)))->getItemForm();
        if(!$player->getInventory()->canAddItem($item)) {
            $player->sendMessage(Translation::getMessage("fullInventory"));
            return;
        }
        $player->getInventory()->addItem($item);
        $player->sendMessage("§a§l[!]§r §8» §7Selected Kit §e" . $name . "§r");
        $kitManager->addToCooldown($kit->getName(), $player->getName(), $time);
    }
}