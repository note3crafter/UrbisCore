<?php

namespace core\entity\forms;

use core\item\CustomItem;
use core\item\enchantment\Enchantment;
use core\CorePlayer;
use core\Elemental;
use core\libs\form\ModalForm;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use core\item\types\MoneyNote;
use core\item\types\XPNote;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\CustomForm;
use core\libs\form\CustomFormResponse;
use core\libs\form\element\Dropdown;
use core\libs\form\element\Input;

class GamblerForm extends CustomForm{
	
    /**
     * GamblerForm constructor.
     *
     * @param CorePlayer $player
     */
    public function __construct(CorePlayer $player) {
        $title = "§l§6Gambler§r";
        $balance = $player->getBalance();
        $xp = $player->getCurrentTotalXp();
        //$crateManager = $player->getCore()->getCrateManager();
        //$vote = $player->getKeys($crateManager->getCrate(Crate::VOTE));
        //$common = $player->getKeys($crateManager->getCrate(Crate::COMMON));
        //$rare = $player->getKeys($crateManager->getCrate(Crate::RARE));
        //$epic = $player->getKeys($crateManager->getCrate(Crate::EPIC));
        //$legendary = $player->getKeys($crateManager->getCrate(Crate::LEGENDARY));
        //$tag = $player->getKeys($crateManager->getCrate(Crate::TAG));
        $elements = [];
        $elements[] = new Dropdown("Options", "What would you like to gamble?", [
            "Balance ($$balance)",
            "XP ($xp)",
            //"Vote Keys ($vote)",
            //"Common Keys ($common)",
            //"Rare Keys ($rare)",
            //"Epic Keys ($epic)",
            //"Legendary Keys ($legendary)",
            //"Tags Keys ($tag)",
        ]);
        $elements[] = new Input("Amount", "How much would you like to gamble?");
        parent::__construct($title, $elements);
    }

    /**
     * @param Player $player
     * @param CustomFormResponse $data
     *
     * @throws TranslationException
     */
    public function onSubmit(Player $player, CustomFormResponse $data): void {
        if(!$player instanceof CorePlayer) {
            return;
        }
        if(count($player->getInventory()->getContents()) === $player->getInventory()->getSize()) {
            $player->sendMessage(Translation::getMessage("fullInventory"));
            return;
        }
        /** @var Dropdown $dropdown */
        $dropdown = $this->getElementByName("Options");
        $option = $dropdown->getOption($data->getInt("Options"));
        $amount = $data->getString("Amount");
        if(!is_numeric($amount)) {
            $player->sendMessage(Translation::getMessage("invalidAmount"));
            return;
        }
        $amount = (int)$amount;
        if($amount <= 0) {
            $player->sendMessage(Translation::getMessage("invalidAmount"));
            return;
        }
        $balance = $player->getBalance();
        $xp = $player->getCurrentTotalXp();
        //$crateManager = $player->getCore()->getCrateManager();
        //$vote = $player->getKeys($crateManager->getCrate(Crate::VOTE));
        //$common = $player->getKeys($crateManager->getCrate(Crate::COMMON));
        //$rare = $player->getKeys($crateManager->getCrate(Crate::RARE));
        //$epic = $player->getKeys($crateManager->getCrate(Crate::EPIC));
        //$legendary = $player->getKeys($crateManager->getCrate(Crate::LEGENDARY));
        //$tag = $player->getKeys($crateManager->getCrate(Crate::TAG));
        switch($option) {
            case "Balance ($$balance)":
                if($amount > $balance) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
				}
				$random = mt_rand(1, 100);
				$chance = mt_rand(5, 10);
				if($amount >= 50000){
					$chance = mt_rand(10, 30);
				}
				if($amount >= 20000){
					$chance = mt_rand(15, 40);
				}
				if($amount >= 1000000){
					$chance = mt_rand(25, 40);
				}
				if($amount >= 2000000){
					$chance = mt_rand(35, 45);
				}
				if($amount >= 5000000){
					$chance = mt_rand(45, 50);
				}
				if($chance >= $random){
					$player->getInventory()->addItem((new MoneyNote($amount))->getItemForm());
					$player->sendMessage(Translation::getMessage("gambleWon", [
						"money" => $amount
					]));
					$player->getServer()->broadcastMessage(Translation::getMessage("gambleWonAnnouncement", [
						"player" => $player->getName(),
						"money" => $amount
					]));
					return;
				} else {
					$player->subtractFromBalance($amount);
					$player->sendMessage(Translation::getMessage("gambleLost", [
						"money" => $amount
                    ]));
                    //Elemental::getInstance()->getLogger()->info($chance . " " . $amount . " " . $random);
					return;
				}
                break;
            case "XP ($xp)":
                if($amount > $xp) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                if($player->getInventory()->getItemInHand()->hasEnchantment(Enchantment::MENDING)) {
                    $player->sendMessage(Translation::getMessage("withdrawXpWhileMending"));
                    return;
				}
				$random = mt_rand(1, 100);
				$chance = mt_rand(15, 25);
				if($amount < 1000){
					$chance = mt_rand(10, 30);
				}
				if($amount < 3000){
					$chance = mt_rand(15, 40);
				}
				if($amount < 7000){
					$chance = mt_rand(25, 40);
				}
				if($amount < 10000){
					$chance = mt_rand(35, 45);
				}
				if($amount < 30000){
					$chance = mt_rand(45, 50);
				}
				if($chance >= $random){
					$player->getInventory()->addItem((new XPNote($amount))->getItemForm());
					$player->sendMessage(Translation::getMessage("gambleWon", [
						"money" => $amount
					]));
					$player->getServer()->broadcastMessage(Translation::getMessage("gambleWonAnnouncement", [
						"player" => $player->getName(),
						"money" => $amount
					]));
					return;
				} else {
					$player->subtractXP($amount);
					$player->sendMessage(Translation::getMessage("gambleLost", [
						"money" => $amount
                    ]));
                    //Elemental::getInstance()->getLogger()->info($chance . " " . $amount . " " . $random);
					return;
				}
                break;
            //case "Vote Keys ($vote)":
                //if($amount > $rare) {
                    //$player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                //$crate = $crateManager->getCrate(Crate::VOTE);
                //$player->removeKeys($crate, $amount);
                //$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                //break;
            //case "Common Keys ($common)":
                //if($amount > $rare) {
                    //$player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                //$crate = $crateManager->getCrate(Crate::COMMON);
                //$player->removeKeys($crate, $amount);
                //$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                //break;
            //case "Rare Keys ($rare)":
                //if($amount > $rare) {
                    //$player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                //$crate = $crateManager->getCrate(Crate::RARE);
                //$player->removeKeys($crate, $amount);
                //$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                //break;
            //case "Epic Keys ($epic)":
                //if($amount > $rare) {
                    //$player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                //$crate = $crateManager->getCrate(Crate::EPIC);
                //$player->removeKeys($crate, $amount);
                //$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                //break;
           //case "Legendary Keys ($legendary)":
                //if($amount > $rare) {
                   // $player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                //$crate = $crateManager->getCrate(Crate::LEGENDARY);
                //$player->removeKeys($crate, $amount);
                //$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                //break;
            //case "Tags Keys ($tag)":
                //if($amount > $rare) {
                    //$player->sendMessage(Translation::getMessage("invalidAmount"));
                    //return;
                //}
                ////$crate = $crateManager->getCrate(Crate::TAG);
                ///$player->removeKeys($crate, $amount);
                //////$player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                /////break;
        }
    }
}