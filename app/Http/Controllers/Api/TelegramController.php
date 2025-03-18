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
        $id = data_get($request, 'message.from.id') ?? data_get($request, 'callback_query.from.id');
        $message = data_get($request, 'message');

        $messageId = data_get($message, 'message_id') ?? data_get($request, 'callback_query.message.message_id');
        $text = data_get($message, 'text', null);

        $callbackData = data_get($request, 'callback_query.data', null);

        $response = [
            'chat_id' => $id,
            'parse_mode' => 'Markdown',
            'reply_to_message_id' => $messageId,
            'text' => $this->handleMessage($id, $text, $callbackData),
        ];

        if (auth()->check()) {
            $response['reply_markup'] = $this->handleReplyMarkup();
        }

        Telegram::bot()->sendMessage($response);
    }

    private function handleMessage($telegramUserId, $message = null, $callbackData = null)
    {
        if (!auth()->check()) {
            return dispatch_sync(new HandleLoginAction($telegramUserId, $message));
        }

        return resolve(AuthenticatedAction::class)->handle($telegramUserId, $message, $callbackData);
    }

    private function handleReplyMarkup()
    {
        return json_encode([
            'inline_keyboard' => [
                [
                    [
                        'text' => '🔴 خروج از حساب',
                        'callback_data' => 'logout'
                    ],
                    [
                        'text' => '📋 مشاهده همه تسک‌ها',
                        'callback_data' => 'get_tasks'
                    ]
                ],
            ]
        ]);
    }
}
