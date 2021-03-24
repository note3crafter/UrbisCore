<?php

namespace core\entity\forms;

use core\item\CustomItem;
use core\item\enchantment\Enchantment;
use core\item\types\EnchantmentBook;
use core\item\types\XPNote;
use core\item\types\notes\BloodyNote;
use core\CorePlayer;
use core\libs\form\ModalForm;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TinkerForm extends ModalForm {

	/**
	 * TinkerForm constructor.
	 *
	 * @param CorePlayer $player
	 */
	public function __construct(CorePlayer $player){
		$item = $player->getInventory()->getItemInHand();
		if($item->getId() !== Item::ENCHANTED_BOOK){
			return;
		}
		$tag = $item->getNamedTagEntry(CustomItem::CUSTOM);
		if(!$tag instanceof CompoundTag){
			return;
		}
		$enchantment = Enchantment::getEnchantment($tag->getInt(EnchantmentBook::ENCHANTMENT));
		$title = TextFormat::BOLD . TextFormat::AQUA . "Tinker";
		$this->title = $title;
        $text = "That item is looking mighty fine today. I'll give you a random amount of experience and a bloody note. Will you accept my offer?";
        $this->content = $text;
        $this->button1 = "gui.yes";
        $this->button2 = "gui.no";
        parent::__construct($title, $text);
    }

	/**
	 * @param Player $player
	 * @param bool   $choice
	 */
	public function onSubmit(Player $player, bool $choice) : void{
		if(!$player instanceof CorePlayer){
			return;
		}
		if($choice == true){
			$item = $player->getInventory()->getItemInHand();
			$player->getInventory()->removeItem($item);
			$item = new XPNote(mt_rand(500, 1000));
			$bloodyNote = new BloodyNote(25000);
			$player->getInventory()->addItem($item->getItemForm());
			$player->getInventory()->addItem($bloodyNote->getItemForm());
			return;
		}
	}
}