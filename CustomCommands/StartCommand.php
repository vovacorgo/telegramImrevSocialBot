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
        $text .= sprintf('Hello %s', $username);

        $this->replyWithMessage(compact('text'));
    }
}
