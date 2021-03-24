<?php

declare(strict_types = 1);

namespace core\libs\form;

use JsonSerializable;

/**
 * Represents an option on a MenuForm. The option is shown as a button and may optionally have an image next to it.
 */
class MenuOption implements JsonSerializable {

    /** @var string */
    private $text;

    /** @var string */
    private $saveName;

    /** @var FormIcon|null */
    private $image;

    /**
     * MenuOption constructor.
     *
     * @param string $text
     * @param FormIcon|null $image
     * @param string|null $saveName
     */
    public function __construct(string $text, ?FormIcon $image = null, string $saveName = null) {
        $this->text = $text;
        $this->image = $image;
        $this->saveName = $saveName;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getRawText(): string {
        return $this->saveName;
    }

    /**
     * @return bool
     */
    public function hasImage(): bool {
        return $this->image !== null;
    }

    /**
     * @return FormIcon|null
     */
    public function getImage(): ?FormIcon {
        return $this->image;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        $json = [
            "text" => $this->text
        ];
        if($this->hasImage()) {
            $json["image"] = $this->image;
        }
        return $json;
    }
}