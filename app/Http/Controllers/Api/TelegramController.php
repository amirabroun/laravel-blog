<?php

namespace App\Http\Controllers\Api;

use App\Actions\AuthenticatedAction;
use App\Actions\HandleLoginAction;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function __invoke(Request $request)
    {
        $telegramUser = $request->message['from'];
        $message = $request->message;

        Telegram::bot()->sendMessage([
            'chat_id' => $telegramUser['id'],
            'reply_to_message_id' => $message['message_id'],
            'text' => $this->handleMessage($message['text'], $telegramUser['id']),
        ]);
    }

    private function handleMessage($message, $telegramUserId)
    {
        if (telegramUserState($telegramUserId) === 'authenticated') {
            return resolve(AuthenticatedAction::class)->handle($message, $telegramUserId);
        }

        return resolve(HandleLoginAction::class)->handle($message, $telegramUserId);
    }
}
