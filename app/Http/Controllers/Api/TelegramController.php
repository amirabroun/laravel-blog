<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    const STATES = [
        'start' => '/start',
        'auth' => '/auth',
        'createPost' => '/createPost',
    ];

    public function test()
    {


        Telegram::bot()->answerCallbackQuery([
            'callback_query_id' => 1541230401797510449 #$callbackQuery['id'] 
        ]);
        $userId = 358845666;
        $messageId = 447;
        $newMessage = 'hi';

        $replyMarkup = Keyboard::make([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'ok',
                        'callback_data' => '/start',
                    ],
                    [
                        'text' => 'cancel',
                        'callback_data' => '/end',
                    ],
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
            'parse_mode' => 'markdown',

        ]);

        $response = Telegram::bot()->sendMessage([
            'chat_id' => $userId,
            'reply_to_message_id' => $messageId,
            'text' => $newMessage,
            'reply_markup' => $replyMarkup
        ]);

        dd($response);
    }

    public function __invoke(Request $request)
    {
        isset($request['message'])
            ? $this->handleMessage($request['message'])
            : $this->handleCallbackQuery($request['callback_query']);
    }

    private function handleMessage($message)
    {
        if (self::STATES['start'] == $message['text']) {
            Telegram::triggerCommand('start', Telegram::getWebhookUpdate());
        }
    }

    private function handleCallbackQuery($callbackQuery)
    {
        Telegram::bot()->answerCallbackQuery([
            'callback_query_id' => $callbackQuery['id']
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

        Telegram::bot()->sendMessage([
            'chat_id' => $userId,
            'reply_to_message_id' => $messageId,
            'text' => $newMessage,
            'reply_markup' => $replyMarkup
        ]);
    }
}
