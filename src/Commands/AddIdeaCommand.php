<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ideas\AbstractClasses\Commands\AbstractCommand;
use Ideas\IdeasDB;
use Longman\TelegramBot\Request;

class AddIdeaCommand extends AbstractCommand {
  protected $name = 'addidea';                      // Your command's name
  protected $description = 'A command for adding a new idea'; // Your command description
  protected $usage = '/addidea';                    // Usage of your command
  protected $version = '1.0.0';                  // Version of your command

  public function execute() {
    $conversation = $this->getConversation();
    $message      = $this->getMessage();
    $user         = $message->getFrom();
    $chat_id      = $message->getChat()->getId();

    $data = [                                  // Set up the new message data
      'chat_id' => $chat_id,                 // Set Chat ID to send the message to
    ];

    if (!$conversation->notes['fileId']) {
      $data['text'] = 'Пришлите фотографию, которая характеризует вашу идею';

      if ($photo = $message->getPhoto()) {
        // Get the original size.
        $photo = end($photo);

        $file = Request::getFile(['file_id' => $photo->getFileId(),]);

        $photoPath = 'https://api.telegram.org/file/bot' . $this->telegram->getApiKey() . '/' . $file->getResult()->getFilePath();
        $fileId    = $photo->getFileId();

        $data['photo']                 = $fileId;
        $ideaId                        = IdeasDB::createIdea(['userId' => $user->getId(), 'photoPath' => $photoPath, 'fileId' => $fileId]);
        $conversation->notes['fileId'] = $fileId;
        $conversation->notes['ideaId'] = $ideaId;
        $data['text']                  = 'Теперь пришлите описание идеи';
      }
    } else {
      $data['text'] = 'Пришлите описание идеи';
      $text         = $message->getText();
      if ($text) {
        IdeasDB::setDescription(['ideaId' => $conversation->notes['ideaId'], 'description' => $text]);
        $data['text'] = 'Ваша идея принята!';
        $conversation->stop();
      }
    }

    if ($conversation->exists()) {
      $conversation->update();
    }
    return Request::sendMessage($data);        // Send message!
  }
}