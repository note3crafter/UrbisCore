<?php



declare(strict_types = 1);



namespace core\command\invmenuforms;



use core\Urbis;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use core\command\task\TeleportTask;
use core\item\types\EnchantmentBook;
use core\item\ItemManager;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;



class EnchantsMenu {

	public function __construct(CorePlayer $player)

	{
		$this->player = $player;

		$this->menu = InvMenu::create(InvMenu::TYPE_CHEST);

		$this->menu->readonly(true);

		$this->inventory = $this->menu->getInventory();

		$this->menu->setName(C::BOLD . C::AQUA . "Custom Enchants" . C::RESET);

		$overlay = Item::get(Item::LAVA, 11);

		$overlay->setCustomName(" ");

		$overlay_2 = Item::get(Item::STAINED_GLASS_PANE, 3);

		$overlay_2->setCustomName(" ");

		$enchant = Item::get(Item::BOOK);

		$enchant->setCustomName("§r§l§5RANDOM ENCHANTMENT");

		$enchant->setLore(["§r§7Cost: §d15 XP\n\n§r§eTap to purchase\n"]);

		$this->inventory->setItem(0, $overlay);

		$this->inventory->setItem(1, $overlay);

		$this->inventory->setItem(2, $overlay);

		$this->inventory->setItem(3, $overlay);

		$this->inventory->setItem(4, $overlay);

		$this->inventory->setItem(5, $overlay);

		$this->inventory->setItem(6, $overlay);

		$this->inventory->setItem(7, $overlay);

		$this->inventory->setItem(8, $overlay);

		$this->inventory->setItem(9, $overlay);

		$this->inventory->setItem(10, $overlay_2);

		$this->inventory->setItem(11, $overlay_2);

		$this->inventory->setItem(12, $overlay_2);

		$this->inventory->setItem(13, $enchant);

		$this->inventory->setItem(14, $overlay_2);

		$this->inventory->setItem(15, $overlay_2);

		$this->inventory->setItem(16, $overlay_2);

		$this->inventory->setItem(17, $overlay);



		$this->inventory->setItem(18, $overlay);

		$this->inventory->setItem(19, $overlay);

		$this->inventory->setItem(20, $overlay);

		$this->inventory->setItem(21, $overlay);

		$this->inventory->setItem(22, $overlay);

		$this->inventory->setItem(23, $overlay);

		$this->inventory->setItem(24, $overlay);

		$this->inventory->setItem(25, $overlay);

		$this->inventory->setItem(26, $overlay);

		$this->menu->setListener(

			function(CorePlayer $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool {
				if($itemClicked->getId() === Item::STAINED_GLASS_PANE) {
					return true;
                }
                if($itemClicked->getId() === Item::LAVA) {
					return true;
				}
				if($itemClicked->getId() === Item::BOOK) {
                    if($player->getXpLevel() < 15) {
                        $player->sendMessage(Translation::getMessage("notEnoughLevels"));
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                    }
                    $item = (new EnchantmentBook(ItemManager::getRandomEnchantment()))->getItemForm();
                    $player->subtractXpLevels(15);
                    $player->sendMessage(Translation::getMessage("buy", [
                        "amount" => TextFormat::GREEN . "1",
                        "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
                        "price" => TextFormat::LIGHT_PURPLE . "15 XP levels",
                    ]));
                    $player->getInventory()->addItem($item);
                    return true;
                }
				return true;
			}
		);
	}

	public function sendMenu() {

		$this->menu->send($this->player);
	}
}