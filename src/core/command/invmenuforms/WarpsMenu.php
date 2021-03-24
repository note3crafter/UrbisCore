<?php



declare(strict_types = 1);



namespace core\command\invmenuforms;



use core\Urbis;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use core\command\task\TeleportTask;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;



class WarpsMenu {

	public function __construct(CorePlayer $player)

	{
		$this->player = $player;

		$this->menu = InvMenu::create(InvMenu::TYPE_CHEST);

		$this->menu->readonly(true);

		$this->inventory = $this->menu->getInventory();

		$this->menu->setName(C::BOLD . C::AQUA . "Warps" . C::RESET);

		$overlay = Item::get(Item::STAINED_GLASS_PANE, 7);

		$overlay->setCustomName(" ");

		$overlay_2 = Item::get(Item::STAINED_GLASS_PANE, 3);

		$overlay_2->setCustomName(" ");

		$spawn = Item::get(Item::PRISMARINE_SHARD);

		$spawn->setCustomName(C::BOLD . C::GREEN . "Spawn");

		$spawn->setLore(["\n§7Teleport to the spawn area where you cannot be damaged!\n\n§8(§a§lSAFEZONE§r§8)\n"]);

		$pvp = Item::get(Item::DIAMOND_SWORD);

		$pvp->setCustomName(C::BOLD . C::RED . "Warzone");

		$pvp->setLore(["\n§7Teleport to the warzone where you can pvp and rule!\n\n§8(§c§lWARZONE§r§8)\n"]);

		$boss = Item::get(Item::BLAZE_POWDER);

		$boss->setCustomName(C::BOLD . C::DARK_RED . "Boss");

		$boss->setLore(["\n§7Teleport to the boss arena where you can\ntry and defeat the boss!\n\n§8(§c§lWARZONE§r§8)\n"]);

		$wild = Item::get(17);

		$wild->setCustomName(C::BOLD . C::DARK_GREEN . "Wilderness");

		$wild->setLore(["\n§7Teleport to the wilderness and\nmake a base or go on a search to find one!\n\n§8(§c§lWARZONE§r§8)\n"]);

		$end = Item::get(Item::ENDER_EYE);

		$end->setCustomName(C::BOLD . C::BLACK . "E" . C::WHITE . "n" . C::BLACK . "d" . C::RESET);

		$end->setLore(["\n§7Teleport to the end of the world...\nYou will face consequences..\n\n§8(§c§lWARZONE§r§8)\n"]);

		$nether = Item::get(Item::NETHER_BRICK);

		$nether->setCustomName("§c§lN§4e§ct§4h§ce§4r§r");

		$nether->setLore(["\n§7Teleport to the place below bedrock\nWhich is the perfect place to make a base!\n\n§8(§c§lWARZONE§r§8)\n"]);

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

		$this->inventory->setItem(11, $pvp);

		$this->inventory->setItem(12, $boss);

		$this->inventory->setItem(13, $wild);

		$this->inventory->setItem(14, $nether);

		$this->inventory->setItem(15, $spawn);

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
				if($itemClicked->getId() === Item::DIAMOND_SWORD) {
					Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($player, Urbis::getInstance()->getServer()->getLevelByName("koth")->getSpawnLocation(), 5), 20);
					$player->removeWindow($this->menu->getInventory(), true);
					return true;
				}
				if($itemClicked->getId() === Item::BLAZE_POWDER) {
					Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($player, Urbis::getInstance()->getServer()->getLevelByName("boss")->getSpawnLocation(), 5), 20);
					$player->removeWindow($this->menu->getInventory(), true);
					return true;
				}



				if($itemClicked->getId() === 17) {

					Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($player, Urbis::getInstance()->getServer()->getLevelByName("FactionsWorld")->getSpawnLocation(), 5), 20);
					$player->removeWindow($this->menu->getInventory(), true);
					return true;
				}



				if($itemClicked->getId() === Item::ENDER_EYE) {

					$player->sendMessage("Not available");
					$player->removeWindow($this->menu->getInventory(), true);
					return true;
				}



				if($itemClicked->getId() === Item::NETHER_BRICK) {

					$player->sendMessage("Not available");
					$player->removeWindow($this->menu->getInventory(), true);
					return true;
				}



				if($itemClicked->getId() === Item::PRISMARINE_SHARD) {

					Urbis::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportTask($player, Urbis::getInstance()->getServer()->getDefaultLevel()->getSpawnLocation(), 5), 20);
					$player->removeWindow($this->menu->getInventory(), true);
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