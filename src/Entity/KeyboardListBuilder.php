<?php
namespace Ideas\Entity;

use Ideas\CommandsList;
use Longman\TelegramBot\Entities\Keyboard;

class KeyboardListBuilder extends AbstractKeyboardBuilder {
  public function __construct($isAdmin) {
    $actions = [CommandsList::COMMANDS_GENERIC[CommandsList::LIKE]];
    if ($isAdmin) {
      $actions[] = CommandsList::COMMANDS_GENERIC[CommandsList::DISLIKE];
    };
    $this->keyboard = new Keyboard(
      $actions,
      [CommandsList::COMMANDS_GENERIC[CommandsList::NEXT]],
      [CommandsList::COMMANDS_GENERIC[CommandsList::START]]
    );

    $this->keyboard
      ->setResizeKeyboard(true)
      ->setSelective(false);
  }
}