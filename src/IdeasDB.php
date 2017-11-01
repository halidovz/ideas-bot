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

  public static function getNextIdea($userId, $isAdmin) {
    $userId = (int) $userId;
    $lastIdeaId = self::getLastViewedIdeaId($userId);
    $idea = self::getIdeaById($userId, $lastIdeaId, $isAdmin);
    if ($idea) {
      self::updateUserIdeaId($userId, is_null($lastIdeaId) ? 0 : $idea['id']);
      $idea = self::getIdeaById($userId, is_null($lastIdeaId) ? 0: $idea['id'], $isAdmin);
    }

    return $idea;
  }

  public static function getCurrentIdea($userId, $isAdmin) {
    $userId = (int) $userId;
    $lastIdeaId = self::getLastViewedIdeaId($userId);
    if (is_null($lastIdeaId)) {
      return self::getNextIdea($userId, $isAdmin);
    }

    return self::getIdeaById($userId, $lastIdeaId, $isAdmin);
  }

  protected static function getIdeaById($userId, $ideaId, $isAdmin) {
    $userId = (int) $userId;
    $ideaId = (int) $ideaId;
    $cond = '';
    if (!$isAdmin) {
      $cond = ' AND approved_at IS NOT NULL';
    }
    $idea = self::$pdo->query("SELECT * FROM ideas WHERE id > $ideaId AND user_id != $userId AND deleted_at IS NULL $cond ORDER BY id LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
    if ($isAdmin && $idea) {
      self::approveIdea($idea['id']);
    }

    return $idea;
  }

  protected static function approveIdea($ideaId) {
    $ideaId = (int) $ideaId;
    self::$pdo->exec("UPDATE ideas set approved_at = NOW() WHERE id = $ideaId");
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

  public static function deleteIdea($userId) {
    $lastId = self::getLastViewedIdeaId($userId);
    $ideaId = self::$pdo->query("SELECT id FROM ideas WHERE id > $lastId AND user_id != $userId AND deleted_at IS NULL ORDER BY id LIMIT 1")->fetch(\PDO::FETCH_COLUMN);
    self::$pdo->exec("UPDATE ideas SET deleted_at = NOW() WHERE id = $ideaId");
  }
}
