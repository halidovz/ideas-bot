<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ideas\AbstractClasses\Commands\AbstractCommand;
use Ideas\CommandsList;
use Ideas\Entity\KeyboardBackBuilder;
use Ideas\Entity\KeyboardListBuilder;
use Ideas\IdeasDB;
use Longman\TelegramBot\Request;

class ListIdeasCommand extends AbstractCommand {
  protected $name = 'listideas';
  protected $description = 'A command for listing existing ideas';
  protected $usage = '/listideas';
  protected $version = '1.0.0';

  public function execute() {
    $conversation = $this->getConversation();
    $message      = $this->getMessage();
    $user         = $message->getFrom();
    $chat_id      = $message->getChat()->getId();

    if ($message->getText() === CommandsList::COMMANDS_GENERIC[CommandsList::LIKE]) {
      $currentIdea = IdeasDB::getCurrentIdea($user->getId());
      IdeasDB::voteForIdea($user->getId(), $currentIdea['id']);
      $idea = IdeasDB::getNextIdea($user->getId());
    }
    else if ($message->getText() === CommandsList::COMMANDS_GENERIC[CommandsList::NEXT]) {
      $idea = IdeasDB::getNextIdea($user->getId());
    }
    else {
      $idea = IdeasDB::getCurrentIdea($user->getId());
    }

    if ($idea) {
      $keyboardBuilder = new KeyboardListBuilder();
    } else {
      $keyboardBuilder = new KeyboardBackBuilder();
    }

    $keyboard = $keyboardBuilder->getKeyboard();

    $data = [
      'chat_id' => $chat_id,
      'reply_markup' => $keyboard
    ];

    if ($idea) {
      $data['text'] = $idea['description'];
      $data['photo'] = $idea['file_id'];
      Request::sendPhoto($data);
    } else {
      $data['text'] = 'Больше нет идей...';
    }

    if ($conversation->exists()) {
      $conversation->update();
    }
    return Request::sendMessage($data);
  }
}