<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    const STATES = [
        '/start',
        '/auth',
        '/createPost',
    ];

    public function __invoke(Request $request)
    {
        $userId = $request['message']['from']['id'];
        $messageId = $request['message']['message_id'];
        $newMessage = 'در حال دولوپ هستم :)';

        $replyMarkup = Keyboard::make([
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

        Telegram::sendMessage([
            'chat_id' => $userId,
            'reply_to_message_id' => $messageId,
            'text' => $newMessage,
            'reply_markup' => $replyMarkup
        ]);
    }

    private function getUserState($userId)
    {
        if (!in_array(telegramCache("state.{$userId}"), self::STATES)) {
            $this->setUserState($userId, '/start');
        }

        return telegramCache("state.{$userId}");
    }

    private function setUserState($userId, $state)
    {
        $state = in_array($state, self::STATES) ? $state : '/start';

        telegramCache("state.{$userId}", $state);
    }

    function storage(Request $request)
    {
        $userId = $request['message']['from']['id'];

        $commandName = substr($this->getUserState($userId), 1);

        Telegram::triggerCommand($commandName, Telegram::getWebhookUpdate());

        Telegram::sendMessage([
            'chat_id' => $userId,
            'text' => 'Hello World',
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => [
                    [
                        'text' => 'ok',
                        'callback_data' => 'data_ok',
                    ],
                    [
                        'text' => 'noO',
                        'callback_data' => 'data_noO',
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }
}
