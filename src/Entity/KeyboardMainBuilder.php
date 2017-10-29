<?php
namespace Ideas\Entity;

use Ideas\CommandsList;
use Longman\TelegramBot\Entities\Keyboard;

class KeyboardMainBuilder extends AbstractKeyboardBuilder {
  public function __construct() {
    $this->keyboard = new Keyboard(
      [CommandsList::COMMANDS_GENERIC[CommandsList::ADD_ADEA]],
      [CommandsList::COMMANDS_GENERIC[CommandsList::LIST_IDEAS]]
    );

    $this->keyboard
      ->setResizeKeyboard(true)
      ->setSelective(false);
  }
}