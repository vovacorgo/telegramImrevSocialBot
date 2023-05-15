<?php

namespace CustomCommands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected  $name = 'start';

    protected array $aliases = ['join'];

    protected  $description = 'Start Command to get you started';

    protected string $pattern = '{username}{age: \d+}';

    public function handle($arguments): void
    {
        $text = '';
        $username = $this->telegram->getWebhookUpdates()->getMessage()->getFrom()->getUsername();
        $text .= sprintf('Вітаю %s', $username);

        $keyboard = [
            ['Правові консультації', 'Пільгові питання', 'Реабілітація'],
            ['Соціальні обласні програми','Консультації з фахівцями','Запит на зворотній зв’язок']

        ];

$reply_markup = $this->telegram->replyKeyboardMarkup([
    'keyboard' => $keyboard,
    'resize_keyboard' => true,
    'one_time_keyboard' => true
]);

 $this->telegram->sendMessage([
    'chat_id' => $this->update->getMessage()->getChat()->getId(),
    'text' => $text,
    'reply_markup' => $reply_markup
]);

    }
}
