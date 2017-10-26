<?php

namespace Ideas\AbstractClasses\Commands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Conversation;

abstract class AbstractCommand extends Command {
  protected $name;

  /**
   * @return Conversation|null
   */
  protected function getConversation() {
    $message = $this->getMessage();
    if ($message) {
      $user = $message->getFrom();
      return new Conversation(
        $user->getId(),
        $message->getChat()->getId(),
        $this->name !== 'genericmessage' ? $this->name : null
      );
    }

    return null;
  }
}