<?php

namespace App\Actions;

use App\Models\Task;
use App\Helper\DateOffsetParser;

class AuthenticatedAction
{
    public function handle($telegramUserId, $message, $callbackData)
    {
        return match (true) {
            $this->isSaveTaskCommand($message) => $this->saveTask($message),
            $callbackData == 'get_tasks' => $this->getTasks(),
            $callbackData == 'logout' => $this->logout($telegramUserId),
            default => "اوه! متاسفم، نتونستم دقیقا متوجه بشم که چی می‌خواهید. "
                . PHP_EOL . "لطفا بیشتر توضیح بدید تا بهتر بتونم کمک کنم. 😊"
        };
    }

    private function saveTask($message)
    {
        list($title, $start) = explode('،', $message, 2);

        $task = new Task([
            'title' => trim($title),
            'start' => DateOffsetParser::calculateOffset(trim($start)),
        ]);

        auth()->user()->tasks()->save($task);

        return 'تسک با موفقیت ذخیره شد';
    }

    private function getTasks()
    {
        $tasks = auth()->user()->tasks()->get();

        return $tasks->map(
            fn($task) => $this->getTaskTitle($task->title, $task->start)
        )->implode(PHP_EOL . PHP_EOL);
    }

    private function getTaskTitle($taskTitle, $start)
    {
        $formattedDate = toJalali($start, 'd %B، ساعت H:i');
        $relativeTime  = diffForHumans($start);

        return "$formattedDate $taskTitle ($relativeTime)";
    }

    private function logout($telegramUserId)
    {
        telegramUserState($telegramUserId, 'waiting_for_username');
        telegramAuthUser($telegramUserId, null);

        return 'شما از حساب خود خارج شدید. برای لاگین مجدد یوزرنیم خود را وارد کنید.';
    }

    private function isSaveTaskCommand($message)
    {
        return isCommandMatched($message, [
            'ساعت',
            'زمان',
            'روز',
            'تاریخ',
            'از',
            'تا',
            'فردا',
            'بعد از',
            'قبل از',
            'در ساعت',
            'در روز',
            'در تاریخ',
            'برای ساعت',
            'برای روز',
            'در مدت',
            'بین',
            'از ساعت',
            'تا ساعت',
            'در روزهای',
            'در هفته'
        ]);
    }
}
