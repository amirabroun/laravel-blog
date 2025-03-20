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
        [$id, $messageId, $message, $callbackData, $replyText] = $this->getTelegramUserData($request);

        [$messageResponse, $replyMarkupResponse] = $this->handleMessage($id, $message, $callbackData, $replyText);

        $response = [
            'chat_id' => $id,
            'parse_mode' => 'Markdown',
            'text' => $messageResponse,
        ];

        if ($callbackData == null) {
            $response['reply_to_message_id'] = $messageId;
        }

        if (auth()->check()) {
            $response['reply_markup'] = $this->prepareReplyMarkup($replyMarkupResponse);
        }

        Telegram::bot()->sendMessage($response);
    }

    private function getTelegramUserData($request)
    {
        $isCallback = data_get($request, 'callback_query') !== null;

        $id = data_get($request, $isCallback ? 'callback_query.from.id' : 'message.from.id');
        $messageId = data_get($request, $isCallback ? 'callback_query.message.message_id' : 'message.message_id');
        $message = data_get($request, 'message.text');
        $callbackData = data_get($request, 'callback_query.data');
        $replyText = data_get($request, $isCallback ? 'callback_query.message.reply_to_message.text' : 'message.reply_to_message.text');

        return [$id, $messageId, $message, $callbackData, $replyText];
    }

    private function handleMessage($telegramUserId, $message = null, $callbackData = null, $replyText = null)
    {
        $action = auth()->check()
            ? new AuthenticatedAction($telegramUserId, $message, $callbackData, $replyText)
            : new HandleLoginAction($telegramUserId, $message);

        $result = dispatch_sync($action);

        return is_array($result) ? $result : [$result, null];
    }

    private function prepareReplyMarkup($replyMarkup = null)
    {
        $keyboards = $this->defaultReplyMarkup();

        if ($replyMarkup) {
            $keyboards[] = [$replyMarkup];
        }

        return json_encode([
            'inline_keyboard' => $keyboards
        ]);
    }

    private function defaultReplyMarkup()
    {
        return [
            [
                ['text' => '🔴 خروج از حساب', 'callback_data' => 'logout'],
                ['text' => '📋 مشاهده همه تسک‌ها', 'callback_data' => 'get_tasks']
            ]
        ];
    }
}
