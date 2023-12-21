<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

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

        $message = $this->getUpdate()->getMessage();

        $this->replyWithMessage([
            'reply_to_message_id' => $message->id,
            'text' => $this->text($message->from),
            'reply_markup' => $this->replyMarkup()
        ]);
    }

    private function text($user)
    {
        $fullName = $user->first_name . ' ' . $user->last_name;

        $text = "سلام {$fullName}! به ربات ما خوش اومدی! ";
        $text .= PHP_EOL . PHP_EOL;
        foreach ($this->nextSteps as $name => $description) {
            $text .= sprintf('/%s  : %s' . PHP_EOL, $name, $description);
        }

        return $text .= PHP_EOL . ' << در حال دولوپ هستم :) >>';
    }

    private function replyMarkup()
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'ok',
                        'callback_data' => 'ok',
                    ],
                    [
                        'text' => 'cancel',
                        'callback_data' => 'cancel',
                    ],
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
    }
}
