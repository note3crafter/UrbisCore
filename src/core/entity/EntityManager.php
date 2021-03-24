<?php

declare(strict_types = 1);

namespace core\entity;

use core\combat\boss\heroes\Phoenix;
use core\command\forms\ChangeLogForm;
use core\command\forms\QuestMainForm;
use core\command\forms\InfoBoyForm;
use core\command\forms\EnchanterForm;
use core\command\forms\PostManForm;
use core\entity\forms\GamblerForm;
use core\entity\forms\TinkerForm;
use core\command\forms\ShopForm;
use core\command\forms\BlackSmithForm;
use core\entity\forms\AlchemistConfirmationForm;
use core\entity\forms\AlchemistConfirmationForm2;
use core\command\invmenuforms\BlackSmithMenu;
use core\command\forms\EnchantmentShopForm;
use core\entity\forms\HeadHuntConfirmationForm;
use core\entity\npc\NPC;
use core\entity\npc\NPCListener;
use core\entity\types\Blaze;
use core\entity\types\Cow;
use core\entity\types\FallingBlock;
use core\entity\types\IronGolem;
use core\entity\types\Pig;
use core\entity\types\PrimedTNT;
use core\entity\types\Skeleton;
use core\entity\types\Slime;
use core\entity\types\Spider;
use core\entity\types\Zombie;
use core\item\CustomItem;
use core\Urbis;
use core\entity\task\ExplosionQueueTask;
use core\CorePlayer;
use core\translation\Messages;
use core\translation\Translation;
use core\entity\types\Rabbit;
use core\utils\Utils;
use pocketmine\block\Bedrock;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockToolType;
use pocketmine\block\Obsidian;
use pocketmine\entity\Entity;
use pocketmine\entity\Explosive;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use pocketmine\item\Durable;
use core\command\invmenuforms\EnchantsMenu;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class EntityManager {

    const STACK_TAG = "Stack";

    const STACK_SIZE = "{SIZE}";

    const STACK_NAME = "{NAME}";

    /** @var Urbis */
    private $core;

    /** @var ExplosionQueueTask */
    private $explosionQueue;

    /** @var NPC[] */
    private $npcs = [];

    /** @var string */
    private static $nametag;

    /**
     * EntityManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->explosionQueue = new ExplosionQueueTask();
        $core->getScheduler()->scheduleRepeatingTask($this->explosionQueue, 1);
        $core->getServer()->getPluginManager()->registerEvents(new EntityListener($core), $core);
        $core->getServer()->getPluginManager()->registerEvents(new NPCListener($core), $core);
        self::$nametag = "§2x§a" . self::STACK_SIZE . "§a " . self::STACK_NAME;
        $this->init();
    }

    public function init() {
        Entity::registerEntity(Skeleton::class,true);
        Entity::registerEntity(Rabbit::class,true);
        Entity::registerEntity(Slime::class,true);
        Entity::registerEntity(Zombie::class,true);
        Entity::registerEntity(PrimedTNT::class, true);
        Entity::registerEntity(Blaze::class, true);
        Entity::registerEntity(Cow::class, true);
        Entity::registerEntity(IronGolem::class, true);
        Entity::registerEntity(Pig::class, true);
        Entity::registerEntity(Spider::class, true);
        Entity::registerEntity(FallingBlock::class, true, ['FallingSand', 'minecraft:falling_block']);
        //Heroes
        Entity::registerEntity(Phoenix::class, true);

		BlockFactory::registerBlock(new class() extends Bedrock {

			/**
			 * @return float
			 */
			public function getBlastResistance() : float {
				return 36.41;
			}

			/**
			 * @return int
			 */
			public function getToolType() : int{
				return BlockToolType::TYPE_PICKAXE;
			}

			/**
			 * @return int
			 */
			public function getToolHarvestLevel() : int{
				return TieredTool::TIER_DIAMOND;
			}

			/**
			 * @return float
			 */
			public function getHardness() : float{
				return 999999;
			}

			/**
			 * @param Item $item
			 *
			 * @return Item[]
			 */
			public function getDropsForCompatibleTool(Item $item): array {
				return [
					Item::get(Item::BEDROCK, 0, 1)
				];
			}

			/**
			 * @param Item $item
			 *
			 * @return bool
			 */
			public function isBreakable(Item $item): bool {
				return true;
			}
		}, true);
		BlockFactory::registerBlock(new class() extends Obsidian {

			/**
			 * @return float
			 */
			public function getBlastResistance() : float {
				return 36.41;
			}
		}, true);

        $dataList = ["alchemist.json", "astronaut.json", "merchant.json", "updater.json", "enchanter.json", "blacksmith.json", "auctioneer.json", "tinker.json", "gambler.json"];

        /** @var Config */
        $data = [];

        foreach ($dataList as $file) {
            $data[pathinfo($file, PATHINFO_FILENAME)] = new Config($this->core->getDataFolder()."npc/$file");
        }

        $merchant = $data["merchant"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $merchant["skin"];
        $position = new Position($merchant["position"]["x"], $merchant["position"]["y"], $merchant["position"]["z"], $this->core->getServer()->getLevelByName($merchant["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§aMerchant\n§7Punch Me", function(CorePlayer $player) {
            $player->sendForm(new ShopForm());
        }));

        $astronaut = $data["astronaut"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $astronaut["skin"];
        $position = new Position($astronaut["position"]["x"], $astronaut["position"]["y"], $astronaut["position"]["z"], $this->core->getServer()->getLevelByName($astronaut["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§eQuest Maker§f\n§7Punch Me", function(CorePlayer $player) {
            $player->sendForm(new QuestMainForm());
        }));

        $updater = $data["updater"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $updater["skin"];
        $position = new Position($updater["position"]["x"], $updater["position"]["y"], $updater["position"]["z"], $this->core->getServer()->getLevelByName($updater["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§cInformation Boy§f\n§7Punch Me", function(CorePlayer $player) {
            $player->sendForm(new InfoBoyForm());
        }));

        $enchanter = $data["enchanter"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $enchanter["skin"];
        $position = new Position($enchanter["position"]["x"], $enchanter["position"]["y"], $enchanter["position"]["z"], $this->core->getServer()->getLevelByName($enchanter["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§dEnchanter§f\n§7Punch Me", function(CorePlayer $player) {
            $menu = new EnchantsMenu($player);

            $menu->sendMenu();
        }));

        $tinker = $data["tinker"]->getAll();
		$path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $tinker["skin"];
		$position = new Position($tinker["position"]["x"], $tinker["position"]["y"], $tinker["position"]["z"], $this->core->getServer()->getLevelByName($tinker["position"]["level"]));
		$this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, TextFormat::BOLD . TextFormat::AQUA . "Tinker\n" . TextFormat::RESET . TextFormat::GRAY . "§7Punch Me", function(CorePlayer $player) {
			$item = $player->getInventory()->getItemInHand();
			$tag = $item->getNamedTagEntry(CustomItem::CUSTOM);

			if($tag === null and $item->getId() !== Item::ENCHANTED_BOOK) {
				$player->sendMessage(TextFormat::DARK_GRAY . "[" . TextFormat::BOLD . TextFormat::AQUA . "Tinker" . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . "I wanted an enchantment book, but interesting item you got there.");
				return;
			}
			$player->sendForm(new TinkerForm($player));
        }));
        
        $gambler = $data["gambler"]->getAll();
		$path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $gambler["skin"];
		$position = new Position($gambler["position"]["x"], $gambler["position"]["y"], $gambler["position"]["z"], $this->core->getServer()->getLevelByName($gambler["position"]["level"]));
		$this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, TextFormat::BOLD . TextFormat::GOLD . "Gambler\n" . TextFormat::RESET . TextFormat::GRAY . "§7Punch Me", function(CorePlayer $player) {
			$player->sendForm(new GamblerForm($player));
		}));

        $auctioneer = $data["auctioneer"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $auctioneer["skin"];
        $position = new Position($auctioneer["position"]["x"], $auctioneer["position"]["y"], $auctioneer["position"]["z"], $this->core->getServer()->getLevelByName($auctioneer["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§bAuctioneer§f\n§7Punch Me", function(CorePlayer $player) {
            Urbis::getInstance()->getServer()->dispatchCommand($player, "ah");
        }));

        $blacksmith = $data["blacksmith"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $blacksmith["skin"];
        $position = new Position($blacksmith["position"]["x"], $blacksmith["position"]["y"], $blacksmith["position"]["z"], $this->core->getServer()->getLevelByName($blacksmith["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§6Blacksmith§f\n§7Punch Me", function(CorePlayer $player) {
            $item = $player->getInventory()->getItemInHand();
            if(!$item instanceof Durable) {
                $player->sendMessage(Translation::getMessage("invalidItem"));
                return;
            }
            $menu = new BlackSmithMenu($player);

            $menu->sendMenu();

        }));

        $alchemist = $data["alchemist"]->getAll();
        $path = Urbis::getInstance()->getDataFolder() . "npc/skins" . DIRECTORY_SEPARATOR . $alchemist["skin"];
        $position = new Position($alchemist["position"]["x"], $alchemist["position"]["y"], $alchemist["position"]["z"], $this->core->getServer()->getLevelByName($alchemist["position"]["level"]));
        $this->addNPC(new NPC(Utils::createSkin(Utils::getSkinDataFromPNG($path)), $position, "§l§3Alchemist§f\n§7Punch Me", function(CorePlayer $player) {
            $item = $player->getInventory()->getItemInHand();
            if($item->hasEnchantments()) {
                foreach($player->getInventory()->getContents() as $i) {
                    $tag = $i->getNamedTagEntry(CustomItem::CUSTOM);
                    if($tag !== null and $i->getId() === Item::SUGAR) {
                        $player->sendForm(new AlchemistConfirmationForm2());
                        return;
                    }
                }
            }
            $tag = $item->getNamedTagEntry(CustomItem::CUSTOM);
            if($tag === null and $item->getId() !== Item::ENCHANTED_BOOK) {
                $player->sendMessage("§l§3ALCHEMIST§r§7: §fThat's not an enchanted book or an enchanted item, sorry i can't trade anything else.");
                return;
            }
            $player->sendForm(new AlchemistConfirmationForm($player));
        }));
    }

    /**
     * @return ExplosionQueueTask
     */
    public function getExplosionQueue(): ExplosionQueueTask {
        return $this->explosionQueue;
    }

    /**
     * @return NPC[]
     */
    public function getNPCs(): array {
        return $this->npcs;
    }

    /**
     * @param int $entityId
     *
     * @return NPC|null
     */
    public function getNPC(int $entityId): ?NPC {
        return $this->npcs[$entityId] ?? null;
    }

    /**
     * @param NPC $npc
     */
    public function addNPC(NPC $npc): void {
        $this->npcs[$npc->getEntityId()] = $npc;
    }

    /**
     * @param NPC $npc
     */
    public function removeNPC(NPC $npc): void {
        unset($this->npcs[$npc->getEntityId()]);
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public static function canStack(Entity $entity): bool {
        return $entity instanceof Living and (!$entity instanceof Human) and (!$entity instanceof Explosive);
    }

    /**
     * @param Living $entity
     */
    public static function addToStack(Living $entity) {
        $bb = $entity->getBoundingBox()->expandedCopy(16, 16, 16);
        foreach($entity->getLevel()->getNearbyEntities($bb) as $e) {
            if($e->namedtag->hasTag(self::STACK_TAG) and $e instanceof Living and $e->getName() === $entity->getName()) {
                $entity->flagForDespawn();
                self::increaseStackSize($e);
                return;
            }
        }
        self::setStackSize($entity);
    }

    /**
     * @param Living $entity
     * @param int $size
     *
     * @return bool
     */
    public static function setStackSize(Living $entity, int $size = 1): bool {
        $entity->namedtag->setInt(self::STACK_TAG, $size);
        if($size < 1) {
            $entity->flagForDespawn();
            return false;
        }
        self::updateEntityName($entity);
        return true;
    }

    /**
     * @param Living $entity
     * @param int $size
     */
    public static function increaseStackSize(Living $entity, int $size = 1) {
        if($entity->namedtag !== null) {
            self::setStackSize($entity, $entity->namedtag->getInt(self::STACK_TAG, 0) + $size);
        }
    }

    /**
     * @param Living $entity
     * @param int $size
     */
    public static function decreaseStackSize(Living $entity, int $size = 1, bool $drops = true) {
        if($size > 0) {
            $currentSize = $entity->namedtag->getInt(self::STACK_TAG);
            $decr = min($size, $currentSize);
            $newSize = $currentSize - $decr;
            $level = $entity->getLevel();
            if(self::setStackSize($entity, $newSize)) {
                $entity->setHealth($entity->getMaxHealth());
            }
            if($drops){
                for($i = 0; $i < $decr; ++$i) {
                    foreach($entity->getDrops() as $item) {
                        $level->dropItem($entity, $item);
                    }
                }
            }
        }
    }

    /**
     * @param Living $entity
     */
    public static function updateEntityName(Living $entity): void {
        $entity->setNameTag(
            strtr(
                self::$nametag, [
                self::STACK_SIZE => $entity->namedtag->getInt(self::STACK_TAG),
                self::STACK_NAME => strtoupper($entity->getName())
            ])
        );
    }
}