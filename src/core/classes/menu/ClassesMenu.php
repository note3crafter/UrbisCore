<?php



declare(strict_types = 1);



namespace core\classes\menu;



use core\Urbis;

use core\CorePlayer;

use core\translation\Translation;

use core\translation\TranslationException;

use core\libs\muqsit\invmenu\InvMenu;

use core\command\task\TeleportTask;

use core\command\forms\InformationForm;

use pocketmine\inventory\Inventory;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\item\Item;

use pocketmine\Player;

use pocketmine\utils\TextFormat as C;



class ClassesMenu {



	public function __construct(CorePlayer $player)

	{



		$this->player = $player;



		$this->menu = InvMenu::create(InvMenu::TYPE_CHEST);



		$this->menu->readonly(true);



		$this->inventory = $this->menu->getInventory();

		$this->menu->setName(C::BOLD . C::AQUA . "Classes" . C::RESET);



		$overlay = Item::get(Item::STAINED_GLASS_PANE, 7);

		$overlay->setCustomName(" ");



		$overlay_2 = Item::get(Item::STAINED_GLASS_PANE, 3);

		$overlay_2->setCustomName(" ");



		$assassin = Item::get(Item::DIAMOND_SWORD);

		$assassin->setCustomName("§c§lAssassin§r");

		$assassin->setLore(["\n§cSpecial Ability: §r§7Become invisible for 5 seconds and\ninvincible for 1 second while crouching.\n\n§bPermanent Effects\n§r§8§l * §r§7Player speed increased by 0.00025x\n§8§l * §r§7Player size is decreased by 0.05x\n§8§l * §r§7Player takes 0.0005x less damage\n§8§l * §r§7Player hit box is lowered by 0.003x\n§r"]);



		$tank = Item::get(Item::IRON_CHESTPLATE);

		$tank->setCustomName("§6§lTank§r");

		$tank->setLore(["\n§cSpecial Ability: §r§7Gain regeneration II for 3 seconds\nwhen you are below 3 hearts.\n\n§bPermanent Effects\n§r§8§l * §r§7Player speed is decreased by 0.001x\n§r§8§l * §r§7Player size is increased by 0.0005x\n§r§8§l * §r§7Player takes 0.005x less damage\n§r§8§l * §r§7Player gains absorption 6\n§r"]);



		$miner = Item::get(Item::IRON_PICKAXE);

		$miner->setCustomName("§5§lMiner§r");

		$miner->setLore(["\n§cSpecial Ability: §r§7None\n\n§bPermanent Effects\n§r§8§l * §r§7Gain the Haste effect at level IV\n§r"]);



		$grinder = Item::get(Item::STONE_SWORD);

		$grinder->setCustomName("§b§lGrinder§r");

		$grinder->setLore(["\n§cSpecial Ability: §r§7Have a chance to gain double\namount of souls while grinding spawners.\n\n§bPermanent Effects\n§r§8§l * §r§7Player experience earned is increased by 0.2x\n§r"]);



		$normal = Item::get(Item::CRAFTING_TABLE);

		$normal->setCustomName("§a§lNormal§r");

		$normal->setLore(["\n§7Feeling adventurous? This is the perfect class for you!\nYou will not gain any advantages or effects.\nYou will be at a disadvantage!\n"]);



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

		$this->inventory->setItem(11, $assassin);

		$this->inventory->setItem(12, $tank);

		$this->inventory->setItem(13, $miner);

		$this->inventory->setItem(14, $normal);

		$this->inventory->setItem(15, $grinder);

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



					$player->setClass("Assassin");

					$player->applyClass();



					$player->removeWindow($this->menu->getInventory(), true);



					return true;



				}



				if($itemClicked->getId() === Item::IRON_CHESTPLATE) {



					$player->setClass("Tank");

					$player->applyClass();



					$player->removeWindow($this->menu->getInventory(), true);



					



					return true;



				}



				if($itemClicked->getId() === Item::STONE_SWORD) {



					$player->setClass("Grinder");

					$player->applyClass();



					$player->removeWindow($this->menu->getInventory(), true);



					



					return true;



				}



				if($itemClicked->getId() === Item::CRAFTING_TABLE) {



					$player->setClass("Normal");

					$player->applyClass();



					$player->removeWindow($this->menu->getInventory(), true);



				



					return true;



				}



				if($itemClicked->getId() === Item::IRON_PICKAXE) {



					$player->setClass("Miner");

					$player->applyClass();



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

