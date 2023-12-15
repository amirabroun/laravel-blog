<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = '/start';
    protected array $aliases = ['join'];
    protected string $description = 'Start Command to get you started';
    protected string $pattern = '{username}';

    private $nextSteps = [
        '/auth' => 'احراز هویت', 
        '/help' => 'راهنمایی استفاده از بات'
    ];

    public function handle()
    {
        $username = $this->getUpdate()->getMessage()->from->username;

        $this->replyWithMessage([
            'text' => "سلام {$username}! به ربات ما خوش اومدی!"
        ]);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $response = '';
        foreach ($this->nextSteps as $name => $description) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $description);
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
