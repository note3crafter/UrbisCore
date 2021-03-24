<?php

namespace core\command\types;



use core\command\forms\CapeForm;
use core\command\utils\Command;
use core\CorePlayer;
use pocketmine\command\CommandSender;

class CapeFormCommand extends Command
{
    public function __construct()
    {
        parent::__construct("cape", 'Cape form Command', '', ['']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof CorePlayer) {
            return;
        }
        $form = new CapeForm();
        $sender->sendForm($form);
    }
}