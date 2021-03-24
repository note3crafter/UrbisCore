<?php



declare(strict_types = 1);



namespace core\command\invmenuforms;



use core\Urbis;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use core\command\task\TeleportTask;
use core\item\types\raiding\TNTLauncher;
use core\item\types\Artifact;
use core\item\types\moneypouches\CommonPouch;
use core\item\types\boxes\MaskBox;
use core\item\ItemManager;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use core\command\invmenuforms\SpecialsMenu;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;



class ShardCategoryMenu {

	public function __construct(CorePlayer $player)

	{
		$this->player = $player;

		$this->menu = InvMenu::create(InvMenu::TYPE_CHEST);

		$this->menu->readonly(true);

		$this->inventory = $this->menu->getInventory();

		$this->menu->setName("§l§3Shard Category Shop");

		$overlay = Item::get(Item::LAVA, 11);

		$overlay->setCustomName(" ");

		$overlay_2 = Item::get(Item::STAINED_GLASS_PANE, 3);

		$overlay_2->setCustomName(" ");

		$tntlauncher = Item::get(Item::WOODEN_HOE);

		$tntlauncher->setCustomName("§r§l§4TNT§r Launcher");

		$tntlauncher->setLore(["§r§7Cost: §l§33,000 Shards§r\n§r§eTap to purchase.\n"]);

		$maskbox = Item::get(Item::CHEST);

		$maskbox->setCustomName("§r§l§aMask§r Box");

		$maskbox->setLore(["§r§7Cost: §l§375,000 Shards§r\n§r§eTap to purchase.\n"]);

		$commonpouch = Item::get(Item::ENDER_CHEST);

		$commonpouch->setCustomName("§r§l§eCommon§r Money Pouch");

		$commonpouch->setLore(["§r§7Cost: §l§3150 Shards§r\n§r§eTap to purchase.\n"]);

		$artifact = Item::get(Item::NETHER_QUARTZ);

		$artifact->setCustomName("§r§l§fArti§bfact");

		$artifact->setLore(["§r§7Cost: §l§3300 Shards§r\n§r§eTap to purchase.\n"]);
        
        $backpage = Item::get(Item::PAPER);

		$backpage->setCustomName("§r§l§cBack Page");

		$backpage->setLore(["§r§7Previous Page Category.\n§r§eTap to go back.\n"]);

		$this->inventory->setItem(0, $tntlauncher);

		$this->inventory->setItem(1, $maskbox);

		$this->inventory->setItem(2, $artifact);

		$this->inventory->setItem(3, $commonpouch);

		$this->inventory->setItem(22, $backpage);

		$this->menu->setListener(

			function(CorePlayer $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool {
				if($itemClicked->getId() === Item::STAINED_GLASS_PANE) {
					return true;
                }
                if($itemClicked->getId() === Item::WOODEN_HOE) {
					if($player->getShards() < 3000) {
                        $player->sendMessage("§c§l»§r §7You don't have enough shards to purchase this item!");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                    }
                    $item = (new TNTLauncher())->getItemForm();
                    $player->subtractShards(3000);
                    $player->sendMessage(Translation::getMessage("buy", [
                        "amount" => TextFormat::GREEN . "1",
                        "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
                        "price" => TextFormat::LIGHT_PURPLE . "3,000 Shards",
                    ]));
                    $player->getInventory()->addItem($item);
                    return true;
				}
				if($itemClicked->getId() === Item::CHEST) {
					if($player->getShards() < 75000) {
                        $player->sendMessage("§c§l»§r §7You don't have enough shards to purchase this item!");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                    }
                    $item = (new MaskBox())->getItemForm();
                    $player->subtractShards(75000);
                    $player->sendMessage(Translation::getMessage("buy", [
                        "amount" => TextFormat::GREEN . "1",
                        "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
                        "price" => TextFormat::LIGHT_PURPLE . "75,000 Shards",
                    ]));
                    $player->getInventory()->addItem($item);
                    return true;
				}
				if($itemClicked->getId() === Item::ENDER_CHEST) {
					if($player->getShards() < 150) {
                        $player->sendMessage("§c§l»§r §7You don't have enough shards to purchase this item!");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                    }
                    $item = (new CommonPouch())->getItemForm();
                    $player->subtractShards(150);
                    $player->sendMessage(Translation::getMessage("buy", [
                        "amount" => TextFormat::GREEN . "1",
                        "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
                        "price" => TextFormat::LIGHT_PURPLE . "150 Shards",
                    ]));
                    $player->getInventory()->addItem($item);
                    return true;
				}
				if($itemClicked->getId() === Item::NETHER_QUARTZ) {
					if($player->getShards() < 300) {
                        $player->sendMessage("§c§l»§r §7You don't have enough shards to purchase this item!");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                    }
                    $item = (new Artifact())->getItemForm();
                    $player->subtractShards(300);
                    $player->sendMessage(Translation::getMessage("buy", [
                        "amount" => TextFormat::GREEN . "1",
                        "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
                        "price" => TextFormat::LIGHT_PURPLE . "300 Shards",
                    ]));
                    $player->getInventory()->addItem($item);
                    return true;
                }
                if($itemClicked->getId() === Item::PAPER) {
					$menu = new SpecialsMenu($player);

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