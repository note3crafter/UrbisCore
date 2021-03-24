<?php

declare(strict_types = 1);

namespace core\tag;

use core\Urbis;
use core\CorePlayer;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TagManager{

    /** @var array */
    public $tags = [];

    public function __construct(){
       
       
        Server::getInstance()->getPluginManager()->registerEvents(new TagListener($this), Urbis::getInstance());
        $this->register("Urbis", "§7[§6§lURBIS§r§7]§r");
        $this->register("Agro", "§7[§5§lAGRO§r§7]§r");
        $this->register("Simp", "§7[§d§lSIMP§r§7]§r");
        $this->register("DaddyXek", "§7[§f§lDaddy§bXek§r§7]§r");
        $this->register("DaddyNix", "§7[§f§lDaddy§dNix§r§7]§r");
        $this->register("Clown", "§7[§f§lClo§4own§r§7]§r");
        $this->register("PvPGod", "§7[§c§lPvP§dGod§r§7]§r");
        $this->register("KrishIsInsane", "§7[§c§lKrish§fIs§dInsane§r§7]§r");
		$this->register("", "");
    }

    /**
     * @param string $name
     * @param string $format
     */
    public function register(string $name, string $format): void{
        $this->tags[$name] = $format . TextFormat::RESET;
    }

    /**
     * @param CorePlayer $player
     * @param bool          $format
     * @return string|null
     */
    public function getTag(CorePlayer $player, bool $format = false): ?string{
        if(($tag = $player->getCurrentTag()) !== null){
            if($format){
            return $tag;
        }
        }
        return null;
	}
	
	/**
	* @param CorePlayer $player
	* @param string $tag
	* @return bool
	*/
	public function getTagFormat(CorePlayer $player, $tag){
        return $this->tags[$tag];
        //$item->setLore(["\n§l§8[§6+§8]§r §7Tap anywhere to claim this tag! §l§8[§6+§8]§r"]);
        //$nbt = $item->getNamedTag();
        //$nbt->setString("tag", $tag);
        //$item->setNamedTag($nbt);
        //return $item;
	}

    /**
     * @param CorePlayer $player
     * @return array|null
     */
    public function getTagList(CorePlayer $player): ?array{
        $tags = $player->getTags();
        if(count($tags) < 1) return null;
        return $tags;
    }

    /**
     * @param CorePlayer $player
     * @param string        $tag
     * @return bool
     */
    public function giveTag(CorePlayer $player, string $tag): bool{
        if(!isset($this->tags[$tag])){
            $player->sendMessage("§l§c(!) §r§cTag doesn't exist, ($tag).");
            return false;
        }
        $player->addTag($tag);
        return true;
    }

    /**
     * @param CorePlayer $player
     * @param string        $tag
     */
    public function removeTag(CorePlayer $player, string $tag): void{
        if(!isset($this->tags[$tag])){
            $player->sendMessage("§l§c(!) §r§cTag doesn't exist, ($tag).");
            return;
        }
        $player->removeTag($tag);
    }

    /**
     * @param CorePlayer $player
     * @param string        $tag
     * @return bool
     */
    public function setTag(CorePlayer $player, string $tag): bool{
        if(!isset($this->tags[$tag])){
            $player->sendMessage("§l§c(!) §r§cThis Tag doesn't exist, ($tag).");
            return false;
        }
        $this->setForceTag($player, $tag);
        return true;
    }

    /**
     * @param CorePlayer $player
     * @param string|null   $tag
     */
    public function setForceTag(CorePlayer $player, ?string $tag): void{
        $player->setCurrentTag($tag);
    }

    /**
     * @param string $tag
     * @return Item
     */
    public function getTagNote(string $tag): Item{
        $item = Item::get(Item::PAPER);
        $item->setCustomName($this->tags[$tag] . " §7Tag§r");
        $item->setLore(["\n§l§8[§6+§8]§r §7Tap anywhere to claim this tag! §l§8[§6+§8]§r"]);
        $nbt = $item->getNamedTag();
        $nbt->setString("tag", $tag);
        $item->setNamedTag($nbt);
        return $item;
    }
}
