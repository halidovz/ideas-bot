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
use Ideas\Entity\KeyboardBackBuilder;
use Ideas\IdeasDB;
use Longman\TelegramBot\Request;
/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class TopListCommand extends AbstractCommand
{
  /**
   * @var string
   */
  protected $name = 'toplist';
  /**
   * @var string
   */
  protected $description = 'Top 10 ideas';
  /**
   * @var string
   */
  protected $usage = '/toplist';
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
    $conversation = $this->getConversation();
    $message = $this->getMessage();
    $chat_id = $message->getChat()->getId();

    $kyeboardBuilder = new KeyboardBackBuilder();
    $keyboard = $kyeboardBuilder->getKeyboard();

    $ideas = IdeasDB::top10();
    $text = '';

    if ($ideas) {
      foreach ($ideas as $i=>$idea) {
        $text .= ($i+1) . '. ' . $idea['description'] . ' - ' . $idea['cnt'] . " 👍\n";
      }
    } else {
      $text = 'Здесь скоро появится топ 10 самых невероятно крутых идей!';
    }

    $data = [
      'chat_id' => $chat_id,
      'text' => $text,
      'reply_markup'    => $keyboard,
    ];
    return Request::sendMessage($data);
  }
}