<?php



declare(strict_types = 1);



namespace core\command\invmenuforms;

use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\muqsit\invmenu\InvMenu;
use core\command\task\TeleportTask;
use core\item\types\raiding\repair;
use core\command\forms\RepairForm;
use core\command\forms\RenameItemForm;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use core\command\invmenuforms\SpecialsMenu;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as C;

class BlackSmithMenu {

	public function __construct(CorePlayer $player)

	{
		$this->player = $player;

		$this->menu = InvMenu::create(InvMenu::TYPE_HOPPER);

		$this->menu->readonly(true);

		$this->inventory = $this->menu->getInventory();

		$this->menu->setName("§l§6Blacksmith");

		$overlay = Item::get(Item::LAVA, 11);

		$overlay->setCustomName(" ");

		$repair = Item::get(Item::ANVIL);

		$repair->setCustomName("§r§l§6Repair§r §7Item");

		$repair->setLore(["§r§7Repair your Item!§r\n§r§eTap to open menu.\n"]);
        
        $backpage = Item::get(Item::TRIPWIRE_HOOK);

		$backpage->setCustomName("§r§l§5Rename§r §7Item");

        $backpage->setLore(["§r§7Rename your Item!\n§r§eTap to open menu.\n"]);
        
        $this->inventory->setItem(0, $overlay);

        $this->inventory->setItem(1, $repair);
        
        $this->inventory->setItem(2, $overlay);

        $this->inventory->setItem(3, $backpage);

        $this->inventory->setItem(4, $overlay);

		$this->menu->setListener(

			function(CorePlayer $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool {
				if($itemClicked->getId() === Item::STAINED_GLASS_PANE) {
					return true;
                }
                if($itemClicked->getId() === Item::ANVIL) {
					Urbis::getInstance()->getServer()->dispatchCommand($player, "repair");
					Urbis::getInstance()->getServer()->dispatchCommand($player, "repair");
                    $player->removeWindow($this->menu->getInventory(), true);
                    return true;
                }
                if($itemClicked->getId() === Item::TRIPWIRE_HOOK) {
                    $player->sendForm(new RenameItemForm($player));
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