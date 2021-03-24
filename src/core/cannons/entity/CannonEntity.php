<?php

namespace core\cannons\entity;

use core\cannons\tasks\AutoFiringTask;
use core\Urbis;
use core\entity\types\PrimedTNT;
use core\item\types\Cannon;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use core\cannons\CannonManager;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;


class CannonEntity extends Human {

    public const PREFIX = TextFormat::BOLD . TextFormat::BLACK . "[" . TextFormat::RESET . TextFormat::AQUA . "!" . TextFormat::RESET . TextFormat::BOLD . TextFormat::BLACK . "]" . TextFormat::RESET . TextFormat::RED . " ";

    public const TNT_FLAG = "tnt_count";
    public const FIRE_POWER_FLAG = "fire_power";

    private $canshoot = false;

    private $autofiring = false;

    private $autofiringtaskid = null;

    private $player = null;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        $this->setSkin(CannonManager::getSkin());
        parent::__construct($level, $nbt);
        $this->namedtag->setInt(self::TNT_FLAG, 0);
        $this->namedtag->setInt(self::FIRE_POWER_FLAG, 1);
        $this->setScale(3.0);
        $this->setGenericFlag(Entity::DATA_FLAG_HAS_COLLISION, true);
    }

    public function initEntity(): void
    {
        parent::initEntity();
        $this->setSkin(CannonManager::getSkin());
    }

    public function fireShot(): bool {
        if (!$this->canshoot && $this->namedtag->getInt(self::TNT_FLAG) > 0){
            $this->canshoot = true;
        }
        if ($this->canshoot){
            $int = $this->namedtag->getInt(self::TNT_FLAG);
            if ($int > 0){
                $this->namedtag->setInt(self::TNT_FLAG, $int - 1);
                $nbt = new CompoundTag("", [
                    new ListTag("Pos", [
                        new DoubleTag("", $this->x),
                        new DoubleTag("", $this->y),
                        new DoubleTag("", $this->z)
                    ]),
                    new ListTag("Motion", [
                        new DoubleTag("", $this->getDirectionVector()->x),
                        new DoubleTag("", $this->getDirectionVector()->y),
                        new DoubleTag("", $this->getDirectionVector()->z)
                    ]),
                    new ListTag("Rotation", [
                        new FloatTag("", $this->yaw),
                        new FloatTag("", $this->pitch)
                    ]),
                ]);

                ($t = new PrimedTNT($this->getLevel(), $nbt))->setMotion($t->getMotion()->multiply($this->getPower() * 2));
                $t->spawnToAll();
                return true;
            }else{
                $this->canshoot = false;
                return false;
            }
        }
        return false;
    }

    public function onInteract(Player $player, Item $item, Vector3 $clickPos): bool {
        if ($this->player !== null){
            return true;
        }
        $this->player = $player;
        $form = new SimpleForm(function (Player $player, ?int $data){
           if ($data === null){
               $this->player = null;
               return;
           }
            if ($this->autofiring === false){
                switch ($data){
                    case 0:
                        $this->sendInsightForm($player);
                        return;
                    case 1:
                        if (!$this->fireShot()){
                            $player->sendMessage(self::PREFIX . "Cannon has ran out of TnT");
                        }
                        $this->player = null;
                        return;
                    case 2:
                        $this->destroy();
                        $player->getInventory()->addItem(new Cannon());
                        $this->removeAutofireingTask();
                        $this->player = null;
                        return;
                }
            }else{
                switch ($data){
                    case 0:
                        $this->sendInsightForm($player);
                        return;
                    case 1:
                        $this->destroy();
                        $player->getInventory()->addItem(new Cannon());
                        $this->removeAutofireingTask();
                        $this->flagForDespawn();
                        return;
                }
            }
        });
        $form->setTitle(TextFormat::BOLD . TextFormat::BLACK . "[" . TextFormat::RESET . TextFormat::RED . "Cannon" . TextFormat::RESET . TextFormat::BOLD . TextFormat::BLACK . "]");
        $form->addButton(TextFormat::RED . "Cannon Info");
        if ($this->autofiring === false){
            $form->addButton(TextFormat::RED . "Fire!");
        }
        $form->addButton(TextFormat::RED . "PickUpCannon;");
        $player->sendForm($form);
    }

    public function textInBrackets(string $title, string $text): string {
        return TextFormat::BLACK . "[" . TextFormat::RED . $title . TextFormat::BLACK . "]" . TextFormat::AQUA . " $text";
     }

     public function getPower(){
        return $this->namedtag->getInt(self::FIRE_POWER_FLAG);
     }

    public function destroy(){
        $pos = $this->asPosition();
        $level = $this->getLevel();
        $tntamount = $this->namedtag->getInt(self::TNT_FLAG);
        $item = ItemFactory::get(ItemIds::TNT, 0, $tntamount);
        $this->removeAutofireingTask();
        $level->dropItem($pos, $item);
        $this->flagForDespawn();
    }

    public function setPower(int $power){
        $this->namedtag->setInt(self::FIRE_POWER_FLAG, $power);
    }

    public function checkToAddTnt(Player $player, int $amount){
        $count = 0;
        if ($amount === 0){
            return;
        }
        foreach ($player->getInventory()->getContents() as $index => $item){
            if ($count >= $amount){
                break;
            }
            if ($item->getId() === ItemIds::TNT){
                if ($count + $item->getCount() > $amount){
                    $newcount = $amount - $count;
                    $count = $amount;
                    $item->setCount($newcount);
                    $player->getInventory()->setItem($index, $item);
                }else{
                    $count += $item->getCount();
                    $player->getInventory()->setItem($index, ItemFactory::get(0));
                }
            }
        }
        $this->namedtag->setInt(self::TNT_FLAG, $this->namedtag->getInt(self::TNT_FLAG) + $count);
        if ($count === $amount){
            $player->sendMessage(self::PREFIX . "Succesfully added $amount tnt to the cannon!");
        }else{
            $player->sendMessage(self::PREFIX . "Was only able to add $count tnt to the cannon!");
        }
        return;
    }

    public function sendInsightForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data){
            if ($data === null){
                $this->player = null;
                return;
            }
            if (isset($data[1])){
                $this->autoFiring($data[1]);
            }
            if (isset($data[2])){
                $this->setPower($data[2]);
            }
            if (isset($data[3])){
                $this->checkToAddTnt($player, $data[3]);
            }
            $this->player = null;
        });
        $form->setTitle(TextFormat::BOLD . TextFormat::BLACK . "[" . TextFormat::RESET . TextFormat::RED . "Cannon Info" . TextFormat::RESET . TextFormat::BOLD . TextFormat::BLACK . "]");
        $tntcount = $this->namedtag->getInt(self::TNT_FLAG);
        $form->addLabel($this->textInBrackets("TntCount", "$tntcount"));
        $form->addToggle(TextFormat::RED . "AutoFiring", $this->autofiring);
        $form->addSlider(TextFormat::RED . "Power", 1, 10, 1, $this->namedtag->getInt(self::FIRE_POWER_FLAG));
        $form->addSlider(TextFormat::LIGHT_PURPLE . "Add Tnt", 0, 1000, 16, 0);
        $player->sendForm($form);
    }

    public function autoFiring(bool $value){
        $this->autofiring = $value;
        $task = new AutoFiringTask($this);
        if ($value === true){
            if ($this->autofiringtaskid === null){
                Urbis::getInstance()->getScheduler()->scheduleRepeatingTask($task, 20 * 2);
                $this->autofiringtaskid = $task->getTaskId();
            }
        }else{
            $this->removeAutofireingTask();
        }
    }

    public function removeAutofireingTask(){
        if ($this->autofiringtaskid !== null){
            Urbis::getInstance()->getScheduler()->cancelTask($this->autofiringtaskid);
            $this->autofiringtaskid = null;
            $this->autofiring = false;
        }
    }

    public function setAutoFiringTaskId($id){
        $this->autofiringtaskid = $id;
        if ($id === null){
            $this->autofiring = false;
        }
    }

}
