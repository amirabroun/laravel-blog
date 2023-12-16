<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected array $aliases = ['join'];
    protected string $description = 'Start Command to get you started';
    protected string $pattern = '{username}';

    private $nextSteps = [
        'auth' => 'احراز هویت',
        'help' => 'راهنمایی استفاده از بات'
    ];

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $user = $this->getUpdate()->getMessage()->from;
        $username = $user->first_name . ' ' . $user->last_name;

        $response = "سلام {$username}! به ربات ما خوش اومدی! ";
        $response .= PHP_EOL . PHP_EOL;
        foreach ($this->nextSteps as $name => $description) {
            $response .= sprintf('/%s  : %s' . PHP_EOL, $name, $description);
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
