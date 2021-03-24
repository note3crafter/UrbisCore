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
use core\command\invmenuforms\ShardCategoryMenu;
use core\command\invmenuforms\MobCoinsCategoryMenu;
use pocketmine\utils\TextFormat as C;



class SpecialsMenu {

	public function __construct(CorePlayer $player)

	{
		$this->player = $player;

		$this->menu = InvMenu::create(InvMenu::TYPE_CHEST);

		$this->menu->readonly(true);

		$this->inventory = $this->menu->getInventory();

		$this->menu->setName(C::BOLD . C::AQUA . "Specials Categories" . C::RESET);

		$overlay = Item::get(Item::LAVA, 11);

		$overlay->setCustomName(" ");

		$overlay_2 = Item::get(Item::STAINED_GLASS_PANE, 3);

		$overlay_2->setCustomName(" ");

		$shardshop = Item::get(Item::PRISMARINE_SHARD);

		$shardshop->setCustomName("§r§l§bShards§r §7Shop");

        $shardshop->setLore(["\n\n§r§7Purchase Different items in this category.\n§r§eTap to see list of the shop.\n"]);
        
        $mcshop = Item::get(Item::BLAZE_POWDER);

		$mcshop->setCustomName("§r§l§cMob Coins§r §7Shop");

		$mcshop->setLore(["\n\n§r§7Purchase Different items in this category.\n§r§eTap to see list of the shop.\n"]);

		$this->inventory->setItem(11, $overlay_2);

		$this->inventory->setItem(12, $mcshop);

		$this->inventory->setItem(13, $overlay_2);

		$this->inventory->setItem(14, $shardshop);

		$this->inventory->setItem(15, $overlay_2);

		$this->menu->setListener(

			function(CorePlayer $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool {
				if($itemClicked->getId() === Item::STAINED_GLASS_PANE) {
					return true;
                }
                if($itemClicked->getId() === Item::BLAZE_POWDER) {
					$menu = new MobCoinsCategoryMenu($player);

					$menu->sendMenu();
					return true;
				}
                if($itemClicked->getId() === Item::PRISMARINE_SHARD) {
					$menu = new ShardCategoryMenu($player);

					$menu->sendMenu();
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