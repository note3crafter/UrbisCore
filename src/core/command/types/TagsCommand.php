<?php

declare(strict_types = 1);

namespace core\command\types;

use core\command\utils\Command;
use core\Urbis;
use core\CorePlayer;
use core\libs\form\MenuForm;
use core\libs\form\MenuOption;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TagsCommand extends Command {

    /**
     * TagsCommand constructor.
     */
    public function __construct() {
        parent::__construct("tags", "Tags command.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     *
     * @throws TranslationException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof CorePlayer) {
            $sender->sendMessage(Translation::getMessage("noPermission"));
            return;
        }

        $manager = Urbis::getInstance()->getTagManager();
        $tag = $manager->getTag($sender);
        $tags = $manager->getTagList($sender);

        if($tags == null){
            $tags = [];
        }
        if($tag == null){
            $tagformat = "DEFAULT.";
        }else{
            if(!isset($manager->tags[$tag])){
                $sender->sendMessage("§l§c»§r §7Unexpected error, resetting you to default tag.§r");
                $sender->sendMessage("§l§c»§r §7Please retry the command.§r");
                $manager->setForceTag($sender, null);
                $tagformat = "DEFAULT.";
            }else{
                $tagformat = $manager->tags[$tag];
            }
        }

        $owned = [];
        $others = [];
        $elements = [];
        foreach($manager->tags as $name => $format){
            if(in_array($name, $tags)){
                $owned[$name] = $format;
            }else{
                $others[$name] = $format;
            }
        }
        foreach($owned as $name => $format){
            $elements[] = new MenuOption(strval($format) . "\n§l§8§r§aOwned§r§r", null, strval($name));
        }
        foreach($others as $name => $format){
            $elements[] = new MenuOption(strval($format), null, strval($name));
        }
        $elements[] = new MenuOption("Reset");
        $sender->sendForm(new class($elements, $this) extends MenuForm {

            /** @var TagsCommand */
            private $command;

            /**
             *  constructor.
             * @param array $elements
             * @param TagsCommand $command
             */
            public function __construct(array $elements, $command) {
                parent::__construct("Tags", "", $elements);
                $this->command = $command;
            }

            /**
             * @param Player $player
             * @param int $index
             */
            public function onSubmit(Player $player, int $index): void {
                $this->command->onCheck($player, $this->getOption($index)->getRawText());
            }
        });
    }

    /**
     * @param Player $player
     * @param $data
     */
    public function onCheck(Player $player, $data): void{

        if (!$player instanceof CorePlayer) return;

        if($data !== null){
            $manager = Urbis::getInstance()->getTagManager();
            $tags = $manager->getTagList($player);
            if($tags == null){
                $tags = [];
            }
            if($data == "Reset"){
                $data = null;
            }else{
                if(!in_array($data, $tags)){
                    $player->sendMessage("§l§c»§r §7Hmm, It looks like you don't own the §c" . $manager->tags[$data] . " §r§7tag!§r" );
                    return;
                }
            }
            $manager->setForceTag($player, $data);
            $player->sendMessage("§l§a»§r §7You've successfully updated your tag!§r");
        }
    }

}
