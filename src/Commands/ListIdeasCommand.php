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
    $userId = $user->getId();

    $isAdmin = $this->isAdmin();
    if ($message->getText() === CommandsList::COMMANDS_GENERIC[CommandsList::LIKE]) {
      $currentIdea = IdeasDB::getCurrentIdea($userId, $isAdmin);
      IdeasDB::voteForIdea($userId, $currentIdea['id']);
      $idea = IdeasDB::getNextIdea($userId, $isAdmin);
    }
    else if ($message->getText() === CommandsList::COMMANDS_GENERIC[CommandsList::DISLIKE]) {
      IdeasDB::deleteIdea($userId);
      $idea = IdeasDB::getNextIdea($userId, $isAdmin);
    }
    else if ($message->getText() === CommandsList::COMMANDS_GENERIC[CommandsList::NEXT]) {
      $idea = IdeasDB::getNextIdea($userId, $isAdmin);
    }
    else {
      $idea = IdeasDB::getCurrentIdea($userId, $isAdmin);
    }

    if ($idea) {
      $keyboardBuilder = new KeyboardListBuilder($isAdmin);
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