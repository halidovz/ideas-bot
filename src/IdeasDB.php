<?php
namespace Ideas;

use Longman\TelegramBot\DB;

class IdeasDB extends DB
{
  public static function createIdea($params) {
    $userId = (int) $params['userId'];
    $photoPath = $params['photoPath'];
    $fileId = $params['fileId'];

    $st = self::$pdo->prepare("INSERT INTO ideas (user_id, photo_path, file_id) VALUES(?,?,?)");
    $st = $st->execute([$userId, $photoPath, $fileId]);
    return self::$pdo->lastInsertId();
  }

  public static function setDescription($params) {
    $ideaId = (int) $params['ideaId'];
    $description = $params['description'];

    $st = self::$pdo->prepare("UPDATE ideas SET description = ? WHERE id = ?");
    $st->execute([$description, $ideaId]);
  }
}
