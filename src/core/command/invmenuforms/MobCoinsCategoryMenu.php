<?php



declare(strict_types = 1);



namespace core\command\invmenuforms;



use core\Urbis;

use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use core\command\task\TeleportTask;
use core\item\types\raiding\bedrock;
use core\item\types\boxes\obsidian;
use core\item\ItemManager;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use core\command\invmenuforms\SpecialsMenu;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;



class MobCoinsCategoryMenu {

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

		$bedrock = Item::get(Item::BEDROCK);

		$bedrock->setCustomName("§r§l§4Bedrock§r Wall Generator");

		$bedrock->setLore(["§r§7Cost: §l§6350 Mob Coins§r\n§r§eTap to purchase.\n"]);

		$obsidian = Item::get(Item::OBSIDIAN);

		$obsidian->setCustomName("§r§l§0Obsidian§r Wall Generator");

        $obsidian->setLore(["§r§7Cost: §l§680 Mob Coins§r\n§r§eTap to purchase.\n"]);
           
		$stone = Item::get(Item::STONE);

		$stone->setCustomName("§r§l§7Stone§r Wall Generator");

		$stone->setLore(["§r§7Cost: §l§625 Mob Coins§r\n§r§eTap to purchase.\n"]);
        
        $backpage = Item::get(Item::PAPER);

		$backpage->setCustomName("§r§l§cBack Page");

		$backpage->setLore(["§r§7Previous Page Category.\n§r§eTap to go back.\n"]);

		$this->inventory->setItem(0, $bedrock);

        $this->inventory->setItem(1, $obsidian);

        $this->inventory->setItem(2, $stone);

		$this->inventory->setItem(22, $backpage);

		$this->menu->setListener(

			function(CorePlayer $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool {
				if($itemClicked->getId() === Item::STAINED_GLASS_PANE) {
					return true;
                }
                if($itemClicked->getId() === Item::BEDROCK) {
                        $player->sendMessage("§c§l»§r §7Sorry, this is not available at the moment.");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
				}
				if($itemClicked->getId() === Item::STONE) {
                        $player->sendMessage("§c§l»§r §7Sorry, this is not available at the moment.");
                        $player->removeWindow($this->menu->getInventory(), true);
                        return true;
                }
                if($itemClicked->getId() === Item::OBSIDIAN) {
                    $player->sendMessage("§c§l»§r §7Sorry, this is not available at the moment.");
                    $player->removeWindow($this->menu->getInventory(), true);
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