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

$base_keyboard = [
    ['Правові консультації', 'Пільгові питання', 'Реабілітація'],
    ['Соціальні обласні програми','Консультації з фахівцями','Запит на зворотній зв’язок']
];
$telegram->addCommands([
    Telegram\Bot\Commands\HelpCommand::class,

]);
$telegram->addCommand(StartCommand::class);
$update = $telegram->commandsHandler(true);

if($update->getMessage()->has('text'))
{   $text = $update->getMessage()->getText();
    switch ($text){
        case 'Старт':
            $message = "Вітаю";
            $keyboard = $base_keyboard;
            break;
        case 'Пільгові питання':
            $message = "Пільгові питання?";
            $keyboard = $base_keyboard;

            break;
        default:
            $message = "Хибна команда";
            $keyboard = $base_keyboard;
            break;
    }
    $reply_markup = $telegram->replyKeyboardMarkup([
        'keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true
    ]);
    $response = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $message,
        'reply_markup' => $reply_markup
    ]);

}

function env(string $variable){
    return $_ENV[$variable];
}