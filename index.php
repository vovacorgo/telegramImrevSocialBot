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
else {
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
        ['Соціальні обласні програми', 'Консультації з фахівцями', 'Запит на зворотній зв’язок']
    ];
    $telegram->addCommands([
        Telegram\Bot\Commands\HelpCommand::class,

    ]);
    $telegram->addCommand(StartCommand::class);
    $update = $telegram->commandsHandler(true);

    if (!is_null($update) && !is_null($update->getMessage()) && $update->getMessage()->has('text')) {
        if( $update->getMessage()->has('text')) {
            $text = $update->getMessage()->getText();
            switch ($text) {
                case '/start':
                    $keyboard = [];
                    $message = "Слава Україні! 🇺🇦
У цьому чат-боті можна знайти актуальну 
інформацію для ветеранів Хмельниччини і членів їхніх родин щодо правових консультацій,
 пільгових питань, реабілітації, соціальних обласних програм, консультації для родин загиблих тощо.";
                    break;
                case 'Правові консультації':
                    $message = "Правові консультації?";
                    $keyboard = $base_keyboard;
                    break;
                case 'Пільгові питання':
                    $message = "Пільгові питання?";
                    $keyboard = $base_keyboard;
                    break;
                case 'Реабілітація':
                    $message = "Реабілітація?";
                    $keyboard = $base_keyboard;
                    break;
                case 'Консультації з фахівцями':
                    $message = "Консультації з фахівцями?";
                    $keyboard = $base_keyboard;
                    break;
                case 'Запит на зворотній зв’язок':
                    $message = "Запит на зворотній зв’язок?";
                    $keyboard = $base_keyboard;
                    break;
                case 'Соціальні обласні програми':
                    $message = "Соціальні обласні програми?";
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
            $response_data = [
                'chat_id' => $chat_id,
                'reply_markup' => $reply_markup
            ];

            if (isset($message)) {
                $response_data['text'] = $message;
            }
            else
                $response_data['text'] = '';

            $response = $telegram->sendMessage($response_data);
        }
    }
}
function env(string $variable){
    return $_ENV[$variable];
}