<?php

declare(strict_types = 1);

namespace core\quest;

use core\CorePlayer;

class Session {

    /** @var CorePlayer */
    private $owner;

    /** @var int[] */
    private $questValues = [];

    /**
     * Session constructor.
     *
     * @param CorePlayer $owner
     */
    public function __construct(CorePlayer $owner) {
        $this->owner = $owner;
    }

    /**
     * @return CorePlayer
     */
    public function getOwner(): CorePlayer {
        return $this->owner;
    }

    /**
     * @param Quest $quest
     *
     * @return int|null
     */
    public function getQuestProgress(Quest $quest): ?int {
        if(!isset($this->questValues[$quest->getName()])) {
            $this->addQuestProgress($quest);
        }
        return $this->questValues[$quest->getName()] ?? null;
    }

    /**
     * @param Quest $quest
     */
    public function addQuestProgress(Quest $quest): void {
        $this->questValues[$quest->getName()] = 0;
    }

    /**
     * @param Quest $quest
     */
    public function removeQuestProgress(Quest $quest): void {
        if(isset($this->questValues[$quest->getName()])) {
            unset($this->questValues[$quest->getName()]);
        }
    }

    /**
     * @param Quest $quest
     * @param null|int $value
     */
    public function updateQuestProgress(Quest $quest, int $value = 1): void {
        if(!isset($this->questValues[$quest->getName()])) {
            $this->addQuestProgress($quest);
            return;
        }
        if($this->questValues[$quest->getName()] === -1) {
            return;
        }
        if($this->questValues[$quest->getName()] >= $quest->getTargetValue()) {
            $this->questValues[$quest->getName()] = -1;
            return;
        }
        $this->questValues[$quest->getName()] += $value;
    }
}