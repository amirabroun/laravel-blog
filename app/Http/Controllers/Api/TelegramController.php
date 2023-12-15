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

        Telegram::bot()->triggerCommand($commandName, Telegram::getWebhookUpdate());

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
