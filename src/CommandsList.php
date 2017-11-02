<?php
/**
 * Created by PhpStorm.
 * User: zelimhanhalidov
 * Date: 29/10/2017
 * Time: 17:16
 */

namespace Ideas;


use Longman\TelegramBot\Commands\UserCommands\AddIdeaCommand;

class CommandsList {
  const ADD_ADEA = 'addidea';
  const LIST_IDEAS = 'listideas';
  const TOPLIST = 'toplist';
  const START = 'start';
  const LIKE = 'like';
  const DISLIKE = 'remove';
  const NEXT = 'next';

  const COMMANDS_GENERIC = [
    self::ADD_ADEA => '💡 Add idea',
    self::LIST_IDEAS => '⚡ List ideas',
    self::TOPLIST => '🏆 Top 10 ideas',
    self::START => '🏠 Home',
    self::LIKE => '👍 Like',
    self::DISLIKE => '👎 Remove',
    self::NEXT => '➡️ Next',
  ];
}