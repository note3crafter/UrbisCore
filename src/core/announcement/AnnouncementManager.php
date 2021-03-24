<?php

declare(strict_types = 1);

namespace core\announcement;

use core\announcement\task\BroadcastMessagesTask;
use core\announcement\task\RestartTask;
use core\Urbis;
use core\outpost\OutpostCaptureTask;

class AnnouncementManager {

    /** @var Urbis */
    private $core;

    /** @var RestartTask */
    private $restarter;

    /** @var string[] */
    private $messages;

    /** @var int */
    private $currentId = 0;

    /**
     * AnnouncementManager constructor.
     *
     * @param Urbis $core
     */
    public function __construct(Urbis $core) {
        $this->core = $core;
        $this->restarter = new RestartTask($core);
        $this->init();
        $core->getScheduler()->scheduleRepeatingTask(new BroadcastMessagesTask($core), 1200);
        $core->getScheduler()->scheduleRepeatingTask($this->restarter, 20);
    }

    public function init(): void {
       $this->messages = [
        "\n§l§8 » §r§7Follow our server Twitter to stay updated! §l§6Twitter: @UrbisPE§r§7.§r\n",
        "\n§l§8 » §r§7Purchase and sell items to other people using the command §l§e/ah§r§7.§r\n",
        "\n§l§8 » §r§7Don't forget to subscribe to §l§bLykex §r§7on §l§fYou§cTube§r§7.§r\n",
        "\n§l§8 » §r§7Support us by Voting and receive rewards by doing so. Type §l§e/vote§r§7 for more information.§r\n",
        "\n§l§8 » §r§7Fight against people gain power and rewards in the §c§lWarZone §r§7with §l§4OUTPOST§r §7and §l§4KOTH§r §7by doing §l§c/outpost§r§7.§r\n",
        "\n§l§8 » §r§7Join our discord and communicate with other players and people on our community at §l§ehttps://discord.gg/VEaFAbN§r§7.§r\n",
       ];
    }

    /**
     * @return string
     */
    public function getNextMessage(): string {
        if(isset($this->messages[$this->currentId])) {
            $message = $this->messages[$this->currentId];
            $this->currentId++;
            return $message;
        }
        $this->currentId = 0;
        return $this->messages[$this->currentId];
    }

    /**
     * @return RestartTask
     */
    public function getRestarter(): RestartTask {
        return $this->restarter;
    }
}
