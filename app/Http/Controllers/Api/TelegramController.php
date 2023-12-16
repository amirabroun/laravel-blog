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

        $commandName = substr($this->getUserState($userId), 1);
        
        Telegram::triggerCommand($commandName, Telegram::getWebhookUpdate());

        $keyboard = [
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
                 ['0']
        ];
        
        $reply_markup = Telegram::re([
            'keyboard' => $keyboard, 
            'resize_keyboard' => true, 
            'one_time_keyboard' => true
        ]);
        
        $response = Telegram::sendMessage([
            'chat_id' => $userId,
            'text' => 'Hello World', 
            'reply_markup' => $reply_markup
        ]);
        

        return;

        $keyBoard = new Keyboard();
        $keyBoard->inline();

        $keyBoard->button([

            [
                'text' => 'hi',

            ]
        ]);

        $telegram = $request->all();
        Telegram::bot()->sendMessage([
            'chat_id' => $telegram['message']['from']['id'],
            'text' => 'در حال دولوپ هستم :)',
            'reply_to_message_id' => $telegram['message']['message_id'],
            'reply_markup' => '',
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
}
