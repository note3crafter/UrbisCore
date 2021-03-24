<?php


namespace core\traits;


use core\announcement\AnnouncementManager;
use core\area\AreaManager;
use core\auction\AuctionManager;
use core\cannons\CannonManager;
use core\clearlag\ClearLagManager;
use core\combat\CombatManager;
use core\command\CommandManager;
use core\crate\CrateManager;
use core\entity\EntityManager;
use core\envoy\EnvoyManager;
use core\event\EventManager;
use core\faction\FactionManager;
use core\gamble\GambleManager;
use core\item\ItemManager;
use core\kit\KitManager;
use core\level\LevelManager;
use core\mask\MaskManager;
use core\price\PriceManager;
use core\provider\MySQLProvider;
use core\quest\QuestManager;
use core\rank\RankManager;
use core\tag\TagManager;
use core\trade\TradeManager;
use core\update\UpdateManager;
use core\watchdog\WatchdogManager;
use core\koth\KOTHManager;
use core\bounty\BountyManager;
use core\classes\ClassManager;
use core\outpost\OutpostManager;

trait ManagerLoader
{

    public static $MANAGER_LIST = [
        RankManager::class,
        AnnouncementManager::class,
        WatchdogManager::class,
        FactionManager::class,
        EntityManager::class,
        CombatManager::class,
        LevelManager::class,
        UpdateManager::class,
        ItemManager::class,
        TagManager::class,
        KitManager::class,
        CrateManager::class,
        PriceManager::class,
        CommandManager::class,
        EnvoyManager::class,
        QuestManager::class,
        TradeManager::class,
        GambleManager::class,
        EventManager::class,
        MaskManager::class,
        ClearLagManager::class,
        CannonManager::class,
        AuctionManager::class,
        AreaManager::class,
        KOTHManager::class,
        OutpostManager::class,
        BountyManager::class,
        ClassManager::class
    ];

    public function initiateManagers() : bool {
        foreach(self::$MANAGER_LIST as $managerClass) {
            $className      = basename($managerClass);
            $managerName    = substr($className, 0, strpos($className, "Manager"));
            $property       = explode("\\", strtolower($managerName)."Manager");

            try {

                $this->setManager(end($property), new $managerClass($this));

            } catch (\Exception $e) {
                $this->getLogger()->error("Error while initializing $className: ".$e->getMessage()." at line ".$e->getLine());
                return false;
            }
        }
        return true;
    }

}