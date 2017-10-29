<?php
namespace Ideas;

use Longman\TelegramBot\DB;
use function PHPSTORM_META\type;

class IdeasDB extends DB
{
  public static function createIdea($params) {
    $userId = (int) $params['userId'];
    $photoPath = $params['photoPath'];
    $fileId = $params['fileId'];
    $description = $params['description'];

    $st = self::$pdo->prepare("INSERT INTO ideas (user_id, photo_path, file_id, description) VALUES(?,?,?,?)");
    $st = $st->execute([$userId, $photoPath, $fileId, $description]);
    return self::$pdo->lastInsertId();
  }

  public static function setDescription($params) {
    $ideaId = (int) $params['ideaId'];
    $description = $params['description'];

    $st = self::$pdo->prepare("UPDATE ideas SET description = ? WHERE id = ?");
    $st->execute([$description, $ideaId]);
  }

  public static function voteForIdea($userId, $ideaId) {
    $userId = (int) $userId;
    $ideaId = (int) $ideaId;
    self::$pdo->exec("INSERT IGNORE INTO ideas_votes (user_id, idea_id) VALUES($userId, $ideaId)");
  }

  public static function getNextIdea($userId) {
    $userId = (int) $userId;
    $lastIdeaId = self::getLastViewedIdeaId($userId);
    $idea = self::getIdeaById($userId, $lastIdeaId);
    if ($idea) {
      self::updateUserIdeaId($userId, is_null($lastIdeaId) ? 0 : $idea['id']);
      $idea = self::getIdeaById($userId, is_null($lastIdeaId) ? 0: $idea['id']);
    }

    return $idea;
  }

  public static function getCurrentIdea($userId) {
    $userId = (int) $userId;
    $lastIdeaId = self::getLastViewedIdeaId($userId);
    if (is_null($lastIdeaId)) {
      return self::getNextIdea($userId);
    }

    return self::getIdeaById($userId, $lastIdeaId);
  }

  protected static function getIdeaById($userId, $ideaId) {
    $userId = (int) $userId;
    $ideaId = (int) $ideaId;
    return self::$pdo->query("SELECT * FROM ideas WHERE id > $ideaId AND user_id != $userId ORDER BY id LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
  }

  protected static function getLastViewedIdeaId($userId) {
    $userId = (int) $userId;
    $result = self::$pdo->query("SELECT idea_id FROM user_idea WHERE user_id = " . $userId)->fetch(\PDO::FETCH_COLUMN);
    return $result !== FALSE ? $result : null;
  }

  protected static function updateUserIdeaId($userId, $ideaId) {
    $userId = (int) $userId;
    $ideaId = (int) $ideaId;
    self::$pdo->exec("INSERT INTO user_idea (user_id, idea_id) VALUES($userId,$ideaId) ON DUPLICATE KEY UPDATE idea_id = $ideaId");
  }
}
