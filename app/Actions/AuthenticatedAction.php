<?php

namespace App\Actions;

use App\Models\Task;
use App\Helper\DateOffsetParser;

class AuthenticatedAction
{
    public function handle($telegramUserId, $message, $callbackData)
    {
        if ($message != null) {
            if (strpos($message, 'done_task_') === 1) {
                return $this->deleteTask($message);
            }

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

    private function getTasks($extraMessage = '')
    {
        $tasks = auth()->user()->tasks()->get();

        if ($tasks->isEmpty()) {
            return $extraMessage . PHP_EOL . PHP_EOL . __('telegram.no_tasks', [], 'fa');
        }

        $overdueTasks = $tasks->filter(fn($task) => $task->start < now());
        $otherTasks = $tasks->filter(fn($task) => $task->start >= now());

        $formatTask = function ($task) {
            $doneCommand = "/done_task_" . $task->id;
            return $this->getTaskTitle($task->title, $task->start) . PHP_EOL . "حذف: " . $doneCommand;
        };

        $overdueText = $overdueTasks->isNotEmpty()
            ? "🚨 کارهای عقب‌افتاده:" . PHP_EOL . PHP_EOL . $overdueTasks->map($formatTask)->implode(PHP_EOL . PHP_EOL)
            : '';

        $otherText = $otherTasks->isNotEmpty()
            ? "📌 سایر کارها:" .  PHP_EOL . PHP_EOL . $otherTasks->map($formatTask)->implode(PHP_EOL . PHP_EOL)
            : '';

        return $extraMessage . PHP_EOL . PHP_EOL . trim($overdueText . PHP_EOL . PHP_EOL . $otherText);
    }

    private function getTaskTitle($taskTitle, $start)
    {
        $formattedDate = toJalali($start, 'd %B، ساعت H:i');
        $relativeTime  = diffForHumans($start);

        return "$formattedDate $taskTitle ($relativeTime)";
    }

    private function deleteTask($message)
    {
        $taskId = str_replace('/done_task_', '', $message);

        Task::query()->where('id', $taskId)->delete();

        return $this->getTasks(__('telegram.task_deleted', [], 'fa'));
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
