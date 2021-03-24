<?php

namespace core\cannons;

use core\cannons\entity\CannonEntity;
use core\item\types\Cannon;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\Player;

class CannonListener implements Listener{

    public function onEntityDamage(EntityDamageByEntityEvent $event){
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if ($entity instanceof CannonEntity){
            $event->setCancelled();
            if ($damager instanceof Player){
                $entity->onInteract($damager);
            }
        }
    }

    public function interactEvent(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($item->getId() === ItemIds::HORSE_ARMOR_DIAMOND && $item->getDamage() === Cannon::CANON_META){
            $item->setCount($item->getCount() - 1);
            if ($item->getCount() > 1){
                $player->getInventory()->setItemInHand($item);
            }else{
                $player->getInventory()->setItemInHand(ItemFactory::get(0));
            }
            $pos = $player->getPosition();
            $nbt = Entity::createBaseNBT($pos, null, $player->getYaw(), 0);
            $en = new CannonEntity($player->getLevel(), $nbt);
            $en->spawnToAll();
        }
    }

}
