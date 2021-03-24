<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\crate\Crate;
use core\item\enchantment\Enchantment;
use core\item\types\CrateKeyNote;
use core\item\types\MoneyNote;
use core\item\types\ShardsNote;
use core\item\types\XPNote;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\CustomForm;
use core\libs\form\CustomFormResponse;
use core\libs\form\element\Dropdown;
use core\libs\form\element\Input;
use pocketmine\Player;

class WithdrawForm extends CustomForm {

    /**
     * WithdrawForm constructor.
     *
     * @param CorePlayer $player
     */
    public function __construct(CorePlayer $player) {
        $title = "§l§dWithdraw§r";
        $balance = $player->getBalance();
        $shards = $player->getShards();
        $xp = $player->getCurrentTotalXp();
        $crateManager = $player->getCore()->getCrateManager();
        $vote = $player->getKeys($crateManager->getCrate(Crate::VOTE));
        $common = $player->getKeys($crateManager->getCrate(Crate::COMMON));
        $rare = $player->getKeys($crateManager->getCrate(Crate::RARE));
        $epic = $player->getKeys($crateManager->getCrate(Crate::EPIC));
        $legendary = $player->getKeys($crateManager->getCrate(Crate::LEGENDARY));
        $mythic = $player->getKeys($crateManager->getCrate(Crate::MYTHIC));
        $elements = [];
        $elements[] = new Dropdown("Options", "What would you like to withdraw?", [
            "Balance ($$balance)",
            "Shards ($shards)",
            "XP ($xp)",
            "Vote Keys ($vote)",
            "Common Keys ($common)",
            "Rare Keys ($rare)",
            "Epic Keys ($epic)",
            "Legendary Keys ($legendary)",
            "Mythic Keys ($mythic)",
        ]);
        $elements[] = new Input("Amount", "How many would you like to withdraw?");
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
        $shards = $player->getShards();
        $xp = $player->getCurrentTotalXp();
        $crateManager = $player->getCore()->getCrateManager();
        $vote = $player->getKeys($crateManager->getCrate(Crate::VOTE));
        $common = $player->getKeys($crateManager->getCrate(Crate::COMMON));
        $rare = $player->getKeys($crateManager->getCrate(Crate::RARE));
        $epic = $player->getKeys($crateManager->getCrate(Crate::EPIC));
        $legendary = $player->getKeys($crateManager->getCrate(Crate::LEGENDARY));
        $tag = $player->getKeys($crateManager->getCrate(Crate::MYTHIC));
        switch($option) {
            case "Balance ($$balance)":
                if($amount > $balance) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $player->subtractFromBalance($amount);
                $player->getInventory()->addItem((new MoneyNote($amount))->getItemForm());
                break;
            case "Shards ($shards)":
                if($amount > $shards) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $player->subtractShards($amount);
                $player->getInventory()->addItem((new ShardsNote($amount))->getItemForm());
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
                $player->subtractXp($amount);
                $player->getInventory()->addItem((new XPNote($amount))->getItemForm());
                break;
            case "Vote Keys ($vote)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::VOTE);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
            case "Common Keys ($common)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::COMMON);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
            case "Rare Keys ($rare)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::RARE);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
            case "Epic Keys ($epic)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::EPIC);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
            case "Legendary Keys ($legendary)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::LEGENDARY);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
            case "Tags Keys ($tag)":
                if($amount > $rare) {
                    $player->sendMessage(Translation::getMessage("invalidAmount"));
                    return;
                }
                $crate = $crateManager->getCrate(Crate::MYTHIC);
                $player->removeKeys($crate, $amount);
                $player->getInventory()->addItem((new CrateKeyNote($crate, $amount))->getItemForm());
                break;
        }
    }
}
