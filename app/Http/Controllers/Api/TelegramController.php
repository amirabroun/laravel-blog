<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use PhpTelegramBot\FluentKeyboard\InlineKeyboard\InlineKeyboardButton;
use PhpTelegramBot\FluentKeyboard\InlineKeyboard\InlineKeyboardMarkup;
use PhpTelegramBot\FluentKeyboard\ReplyKeyboard\KeyboardButton;
use PhpTelegramBot\FluentKeyboard\ReplyKeyboard\KeyboardButtonPollType;
use PhpTelegramBot\FluentKeyboard\ReplyKeyboard\ReplyKeyboardMarkup;
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

        Request::sendMessage([
            'chat_id' => $userId,
            'text' => 'Hello World',
            'reply_markup' => ReplyKeyboardMarkup::make()
                ->oneTimeKeyboard()
                ->button(KeyboardButton::make('Cancel'))
                ->button(KeyboardButton::make('OK')),
        ]);

        InlineKeyboardMarkup::make()
            ->row([
                InlineKeyboardButton::make('1')->callbackData('page-1'),
                InlineKeyboardButton::make('2')->callbackData('page-2'),
                InlineKeyboardButton::make('3')->callbackData('page-3')
            ])
            ->row([
                InlineKeyboardButton::make('prev')->callbackData('page-prev'),
                InlineKeyboardButton::make('next')->callbackData('page-next')
            ]);

        //
        $keyboard = [
            ['test1', 'test1', 'test1']
        ];
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $reply_markup->inlineButton();
        $reply_markup->button([

            [
                'text' => 'hi',

            ]
        ]);


        $response = Telegram::sendMessage([
            'chat_id' => $userId,
            'text' => 'Hello World',
            'reply_markup' => $reply_markup
        ]);
        $telegram = $request->all();
        Telegram::bot()->sendMessage([
            'chat_id' => $telegram['message']['from']['id'],
            'text' => 'در حال دولوپ هستم :)',
            'reply_to_message_id' => $telegram['message']['message_id'],
            'reply_markup' => [
                ['text' => 'btn_1'],
                ['text' => 'btn_2'],
            ],
            [
                ['text' => 'btn_3'],
                ['text' => 'btn_4'],
            ],
            [
                ['text' => 'btn_5'],
            ]
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
