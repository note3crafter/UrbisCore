<?php

declare(strict_types = 1);

namespace core\item\task;

use core\item\types\ChestKit;
use core\kit\Kit;
use core\Urbis;
use core\CorePlayer;
use core\translation\Translation;
use core\utils\UtilsException;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\Position;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\network\mcpe\protocol\AddItemActorPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class HolyBoxAnimationTask extends Task {

    /** @var int */
    private $runs = 0;

    /** @var array */
    private $items = [];

    /** @var Position */
    private $position;

    /** @var CorePlayer */
    private $player;

    /** @var Kit */
    private $kit;

    /**
     * HolyBoxAnimationTask constructor.
     *
     * @param CorePlayer $player
     * @param Position      $position
     * @param Kit           $kit
     *
     * @throws UtilsException
     */
    public function __construct(CorePlayer $player, Position $position, Kit $kit) {
        $this->position = $position;
        $this->player = $player;
        $this->kit = $kit;
        $position = Position::fromObject($this->position->add(0.5, 2, 0.5), $this->position->getLevel());
        if($this->player->getFloatingText("Godly Kit") === null) {
            $player->addFloatingText($position, "Godly Kit", "§l§cOPENING META BOX\n" . TextFormat::RESET . TextFormat::GRAY . "Stone: None permanent\n" . TextFormat::GRAY . "Emerald: Permanent");
        }
    }

    /**
     * @param int $currentTick
     *
     * @throws UtilsException
     */
    public function onRun(int $currentTick) {
        $this->runs++;
        if($this->runs === 1) {
            $pks = [];
            $pk = new BlockEventPacket();
            $pk->x = $this->position->getFloorX();
            $pk->y = $this->position->getFloorY();
            $pk->z = $this->position->getFloorZ();
            $pk->eventType = 1;
            $pk->eventData = 1;
            $pks[] = $pk;
            $pk = new LevelSoundEventPacket();
            $pk->sound = LevelSoundEventPacket::SOUND_EXPLODE;
            $pk->position = $this->position;
            $pks[] = $pk;
            $this->player->getServer()->batchPackets($this->position->getLevel()->getPlayers(), $pks);
            return;
        }
        $item = null;
        if($this->runs > 1 and $this->runs <= 7) {
            $item = Item::get(Item::EMERALD_BLOCK, 0, 1);
            $this->player->getLevel()->addSound(new ClickSound($this->position));
        }
        if($this->runs >= 8 and $this->runs < 20) {
            $item = Item::get(Item::STONE, 0, 1);
            $this->player->getLevel()->addSound(new ClickSound($this->position));
        }
        if($item !== null) {
            $position = Position::fromObject($this->position->add(0.5, 1, 0.5), $this->position->getLevel());
            $pk = new AddItemActorPacket();
            $pk->item = $item;
            $pk->entityRuntimeId = Entity::$entityCount++;
            $pk->position = $position;
            $this->items[$pk->entityRuntimeId] = $item->getId();
            $this->player->getServer()->broadcastPacket($this->position->getLevel()->getPlayers(), $pk);
            return;
        }
        if(count($this->items) > 1) {
            $item = array_rand($this->items);
            $pk = new RemoveActorPacket();
            $pk->entityUniqueId = $item;
            $this->player->getServer()->broadcastPacket($this->position->getLevel()->getPlayers(), $pk);
            $this->position->getLevel()->addParticle(new DestroyBlockParticle($this->position, Block::get($this->items[$item])));
            $this->position->getLevel()->addSound(new FizzSound($this->position));
            unset($this->items[$item]);
            return;
        }
        if($this->runs > 75) {
            $item = array_rand($this->items);
            $pk = new RemoveActorPacket();
            $pk->entityUniqueId = $item;
            $this->player->getServer()->broadcastPacket($this->position->getLevel()->getPlayers(), $pk);
            $result = $this->items[$item];
            if($result === Item::EMERALD_BLOCK) {
                $this->player->getServer()->broadcastMessage($this->player->getDisplayName() . " §7has permanently obtained the §e{$this->kit->getName()} §7Kit!");
                $this->player->addPermission("permission." . strtolower($this->kit->getName()));
            }
            else {
                $this->position->getLevel()->dropItem($this->position->add(0, 0.5, 0), (new ChestKit($this->kit))->getItemForm());
                    $this->player->getInventory()->addItem((new ChestKit($this->kit))->getItemForm());
                }
            unset($this->items[$item]);
            $this->position->getLevel()->setBlock($this->position, Block::get(Block::AIR));
            $tile = $this->position->getLevel()->getTile($this->position);
            if($tile !== null) {
            }
            if($this->player->getFloatingText("Godly Kit") !== null) {
                $this->player->removeFloatingText("Godly Kit");
            }
            if($this->player->getLevel() !== null) {
                $this->player->getLevel()->addParticle(new ExplodeParticle($this->position));
                $this->player->getLevel()->addSound(new BlazeShootSound($this->position));
            }
            Urbis::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}