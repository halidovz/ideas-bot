<?php
namespace Ideas\Entity;

use Ideas\CommandsList;
use Longman\TelegramBot\Entities\Keyboard;

class KeyboardBackBuilder extends AbstractKeyboardBuilder {
  public function __construct() {
    $this->keyboard = new Keyboard(
      [CommandsList::COMMANDS_GENERIC[CommandsList::START]]
    );

    $this->keyboard
      ->setResizeKeyboard(true)
      ->setSelective(false);
  }
}