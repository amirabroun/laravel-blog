<?php

namespace App\Actions;

use App\Models\Task;
use App\Helper\DateOffsetParser;

class AuthenticatedAction
{
    public function handle($telegramUserId, $message, $callbackData)
    {
        if ($message != null) {
            return $this->saveTask($message);
        }

        return match (true) {
            $callbackData == 'get_tasks' => $this->getTasks(),
            $callbackData == 'logout' => $this->logout($telegramUserId),
            default => __('telegram.error_unknown', [], 'fa'),
        };
    }

    private function saveTask($message)
    {
        $parsed = $this->parseTaskMessage($message);

        if (!$parsed) {
            return __('telegram.error_unknown', [], 'fa');
        }

        auth()->user()->tasks()->save(new Task([
            'title' => $parsed['title'],
            'start' => DateOffsetParser::calculateOffset($parsed['time']),
        ]));

        return __('telegram.task_saved', [], 'fa');
    }

    private function getTasks()
    {
        $tasks = auth()->user()->tasks()->get();

        if ($tasks->isEmpty()) {
            return __('telegram.no_tasks', [], 'fa');
        }

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

        return __('telegram.logged_out', [], 'fa');
    }

    private function parseTaskMessage($message)
    {
        $timePattern = implode('|', array_map('preg_quote',  __('telegram.time_keywords', [], 'fa')));

        $pattern = '/^(.*?)\s*،\s*(' . $timePattern . '.*)$/u';

        if (!preg_match($pattern, $message, $matches)) {
            return null;
        }

        return [
            'title' => trim($matches[1]),
            'time' => trim($matches[2]),
        ];
    }
}
