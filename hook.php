<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/creds.php';


try {
  // Create Telegram API object
  $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

  $commands_paths = [__DIR__ . '/src/Commands',];
  // Add this line inside the try{}
  $telegram->addCommandsPaths($commands_paths);
  $telegram->enableMySql($mysql_credentials);
  $telegram->enableAdmins([748759, 1148457]);

  // Handle telegram webhook request
  $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
  file_put_contents('test.log', $e->getMessage());
  // Silence is golden!
  // log telegram errors
  // echo $e->getMessage();
}