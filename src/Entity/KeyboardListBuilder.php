<?php
namespace Ideas\Entity;

use Ideas\CommandsList;
use Longman\TelegramBot\Entities\Keyboard;

class KeyboardListBuilder extends AbstractKeyboardBuilder {
  public function __construct() {
    $this->keyboard = new Keyboard(
      [CommandsList::COMMANDS_GENERIC[CommandsList::LIKE]],
      [CommandsList::COMMANDS_GENERIC[CommandsList::NEXT]],
      [CommandsList::COMMANDS_GENERIC[CommandsList::START]]
    );

    $this->keyboard
      ->setResizeKeyboard(true)
      ->setSelective(false);
  }
}