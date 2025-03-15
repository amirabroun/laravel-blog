<?php

namespace App\Actions;

class AuthenticatedAction
{
    public function handle($message, $telegramUserId)
    {
        return match (true) {
            isCommandMatched($message, $this->getTaskKeyWords()) => $this->getTasks($message, $telegramUserId),

            isCommandMatched($message, $this->logoutKeyWords()) => $this->logout($telegramUserId),

            default => "اوه! متاسفم، نتونستم دقیقا متوجه بشم که چی می‌خواهید. "
                . PHP_EOL . "لطفا بیشتر توضیح بدید تا بهتر بتونم کمک کنم. 😊"
        };
    }

    private function getTasks()
    {
        $tasks = auth()->user()->tasks()->get();

        return $tasks->map(
            fn($task) => $this->createTaskTitle($task->title, $task->start)
        )->implode(PHP_EOL . PHP_EOL);
    }

    private function createTaskTitle($taskTitle, $start)
    {
        $formattedDate = toJalali($start, 'd %B، ساعت H:i');
        $relativeTime  = diffForHumans($start);

        return "$formattedDate $taskTitle ($relativeTime)";
    }

    private function logout($telegramUserId)
    {
        telegramAuthUser($telegramUserId, null);

        return 'شما از حساب خود خارج شدید.';
    }

    private function getTaskKeyWords()
    {
        return ['تسک ها', 'تسک‌ها', 'کار ها', 'کارها'];
    }

    private function logoutKeyWords()
    {
        return [
            'میخوام لاگ اوت شم',
            'log out',
            'خروج',
            'خداحافظ',
            'sign out',
            'logout',
            'exit',
            'logoff',
            'بیرون برو',
            'از حساب خارج شو',
            'من میخواهم خارج بشم',
            'من میخوام خروج کنم',
            'end session',
            'close account',
        ];
    }
}
