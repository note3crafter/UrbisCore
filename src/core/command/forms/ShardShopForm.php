<?php

declare(strict_types = 1);

namespace core\command\forms;

use core\item\ItemManager;
use core\item\types\boxes\LootBag;
use core\item\types\HolyBox;
use core\item\types\MoneyNote;
use core\item\types\XPNote;
use core\item\types\raiding\TNTLauncher;
use core\Urbis;
use core\UrbisPlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use core\libs\form\FormIcon;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ShardShopForm extends MenuForm {

    /**
     * ShardShopForm constructor.
     *
     * @param UrbisPlayer $player
     */
    //public function __construct(UrbisPlayer $player) {
 //       $title = "§l§cShard Shop";
 //       $text = "Shards: " . $player->getShards();
 //       $options = [];
 //       $options[] = new MenuOption("$100K Cheque 5000 Shards");
 //       $options[] = new MenuOption ("1.5K XP 350 Shards");
 //       $options[] = new MenuOption("x1 Iron Golem Spawner 850 Shards");
 //       $options[] = new MenuOption("x1 Meta Box 1500 Shards");
 //       $options[] = new MenuOption("x1 TNT Launcher 12000 Shards");
 //       parent::__construct($title, $text, $options);
 //   }
//
 //   /**
 //    * @param Player $player
 //    * @param int $selectedOption
 //    *
///     * @throws TranslationException
//     */
///    public function onSubmit(Player $player, int $selectedOption): void {
//        if(!$player instanceof UrbisPlayer) {
 //           return;
//        }
///        $option = $this->getOption($selectedOption);
 //       if($player->getInventory()->getSize() === count($player->getInventory()->getContents())) {
 //           $player->sendMessage(Translation::getMessage("fullInventory"));
//            return;
 //       }
//        switch($option->getText()) {
 //           case "$100K Cheque 5000 Shards":
///               $shards = 5000;
 //               $item = (new MoneyNote(100000))->getItemForm();
  //              break;
 //           case "1.5K XP 350 Shards":
  //              $shards = 350;
  //              $item = (new XPNote(1500))->getItemForm();
 //               break;
 //           case "Normal Lootbag":
 ////               $shards = 250;
   //             $item = (new LootBag(1))->getItemForm();
  //              break;
//            case "x1 TNT Launcher 12000 Shards":
    //            $shards = 12000;
    //            $item = (new TNTLauncher(1500))->getItemForm();
   //             break;
    //        case "x1 Iron Golem Spawner 850 Shards":
  //              $shards = 850;
  //              $item = Item::get(Item::MOB_SPAWNER, 0, 1, new CompoundTag("", [
  //                  new IntTag("EntityId", Entity::IRON_GOLEM)
  //              ]));
  //              $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . "Iron Golem Spawner");
  //              break;
  //          case "x1 Meta Box 1500 Shards":
 //               $kits = Urbis::getInstance()->getKitManager()->getSacredKits();
 //               $kit = $kits[array_rand($kits)];
 //               $shards = 1500;
 //               $item = (new HolyBox($kit))->getItemForm();
  //              break;
  //          default:
  //              return;
  //      }
 ////       if($player->getShards() < $shards) {
   //         $player->sendMessage("You dont have enough shards!");
 //           return;
 //       }
 //       $player->subtractShards($shards);
 //       $player->sendMessage(Translation::getMessage("buy", [
 //           "amount" => TextFormat::GREEN . "1",
 //           "item" => TextFormat::DARK_GREEN . $item->getCustomName(),
  //          "price" => TextFormat::LIGHT_PURPLE . "$shards shards",
  //      ]));
 //       $player->getInventory()->addItem($item);
  //  }
//}