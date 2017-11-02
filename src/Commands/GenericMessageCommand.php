<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use Ideas\AbstractClasses\Commands\AbstractCommand;
use Ideas\CommandsList;
use Longman\TelegramBot\Request;

/**
 * Generic command
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericMessageCommand extends AbstractCommand
{
  /**
   * @var string
   */
  protected $name = 'genericmessage';
  /**
   * @var string
   */
  protected $description = 'Handles generic commands or is executed by default when a command is not found';
  /**
   * @var string
   */
  protected $version = '1.1.0';
  /**
   * Command execute method
   *
   * @return \Longman\TelegramBot\Entities\ServerResponse
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  public function execute()
  {
    $message = $this->getMessage();

    switch ($message->getText()) {
      case CommandsList::COMMANDS_GENERIC[CommandsList::ADD_ADEA]:
        return $this->telegram->executeCommand(CommandsList::ADD_ADEA);
        break;

      case CommandsList::COMMANDS_GENERIC[CommandsList::LIST_IDEAS]:
        return $this->telegram->executeCommand(CommandsList::LIST_IDEAS);
        break;

      case CommandsList::COMMANDS_GENERIC[CommandsList::TOPLIST]:
        return $this->telegram->executeCommand(CommandsList::TOPLIST);
        break;

      case CommandsList::COMMANDS_GENERIC[CommandsList::START]:
        return $this->telegram->executeCommand(CommandsList::START);
        break;
    }

    $conversation = $this->getConversation();
    if ($conversation->exists() && ($command = $conversation->getCommand())) {
      return $this->telegram->executeCommand($command);
    }

    return $this->telegram->executeCommand('start');
  }
}