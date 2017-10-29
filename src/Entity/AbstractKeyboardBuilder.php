<?php
/**
 * Created by PhpStorm.
 * User: zelimhanhalidov
 * Date: 29/10/2017
 * Time: 16:47
 */

namespace Ideas\Entity;


use Longman\TelegramBot\Entities\Keyboard;

abstract class AbstractKeyboardBuilder {
  /** @var  Keyboard */
  protected $keyboard;

  /**
   * @return Keyboard
   */
  public function getKeyboard() {
    return $this->keyboard;
  }
}