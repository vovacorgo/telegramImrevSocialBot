<?php
    require __DIR__.'/vendor/autoload.php';


use CustomCommands\StartCommand;
use Telegram\Bot\Api;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$telegramBotToken = env('TELEGRAM_BOT_TOKEN');

if (!$telegramBotToken) {
    throw new Exception('TELEGRAM_BOT_TOKEN environment variable not set -'.__DIR__);
}

// \Illuminate\Support\Arr::get(env('TELEGRAM_BOT_TOKEN'), null);

$telegram = new Api($telegramBotToken);
    $result = $telegram->getWebhookUpdates();
$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
$first_name = $result["message"]["from"]["first_name"];
$last_name = $result["message"]["from"]["last_name"];
$username = $first_name . ' ' . $last_name;

$keyboard = [
    ['New',],
];

$reply_markup = $telegram->replyKeyboardMarkup([
    'keyboard' => $keyboard,
    'resize_keyboard' => true,
    'one_time_keyboard' => true
]);

$response = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => 'Buttons',
    'reply_markup' => $reply_markup
]);
$telegram->addCommands([
    Telegram\Bot\Commands\HelpCommand::class,

]);
$telegram->addCommand(StartCommand::class);
$update = $telegram->commandsHandler(true);

if($update->getMessage()->has('text'))
{   $text = $update->getMessage()->getText();
    switch ($text){
        case 'New':
            $message = "Вітаю";
            $keyboard = [
                ['Ліво', 'Право', 'Центер'],
                ['Назад']

            ];
            break;
    }
    $reply_markup = $telegram->replyKeyboardMarkup([
        'keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true
    ]);
}

function env(string $variable){
    return $_ENV[$variable];
}