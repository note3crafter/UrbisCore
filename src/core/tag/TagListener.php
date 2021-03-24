<?php

declare(strict_types = 1);

namespace core\tag;


use core\CorePlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\StringTag;
use pocketmine\level\sound\BlazeShootSound;

class TagListener implements Listener{

    /** @var TagManager */
    private $tag;

    /**
     * TagListener constructor.
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager){
        $this->tag = $tagManager;
    }

    public function onInteract(PlayerInteractEvent $e): void{
        /** @var CorePlayer $p */
        $p = $e->getPlayer();
        $inv = $p->getInventory();
        $nbt = $e->getItem()->getNamedTag();

        if($nbt->hasTag("tag", StringTag::class)){
            $tags = $this->tag->getTagList($p);
            if($tags == null){
                $tags = [];
            }
            if(in_array($nbt->getString("tag"), $tags)){
                $p->sendMessage("§l§c(!) §r§cYou already own this tag!§r");
                return;
            }
            $this->tag->giveTag($p, $nbt->getString("tag"));
            $item = $inv->getItemInHand();
            $item->count--;
            $inv->remove($item);
            $p->sendMessage("§l§a(!) §r§cYou've successfully claimed this tag, use /tags to use them!§7.§r");
            $p->playXpLevelUpSound();
	    $p->getLevel()->addSound(new BlazeShootSound($p));
        }
    }
}