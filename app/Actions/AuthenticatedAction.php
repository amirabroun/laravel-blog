<?php

namespace App\Actions;

use App\Models\Task;
use App\Helper\DateOffsetParser;

class AuthenticatedAction
{
    public function handle($telegramUserId, $message, $callbackData)
    {
        if ($callbackData == 'get_tasks') {
            return $this->getTasks();
        }

        if ($callbackData == 'logout') {
            (new HandleLoginAction($telegramUserId))->logout();
        }

        if (strpos($message, 'donetask') === 1) {
            return $this->deleteTask($message);
        }

        if ($parsed = $this->parseTaskMessage($message)) {
            return $this->saveTask($parsed);
        }

        return __('telegram.error_unknown', [], 'fa');
    }

    private function saveTask($parsed)
    {
        auth()->user()->tasks()->save(new Task([
            'title' => $parsed['title'],
            'start' => $parsed['start'],
        ]));

        return __('telegram.task_saved', [], 'fa');
    }

    private function getTasks($extraMessage = '')
    {
        $tasks = auth()->user()->tasks()->orderBy('start')->get();

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
        $taskId = str_replace('/donetask', '', $message);

        auth()->user()->tasks()->where('id', $taskId)->delete();

        return $this->getTasks(__('telegram.task_deleted', [], 'fa'));
    }

    private function parseTaskMessage($message)
    {
        $title = $message;
        $start = $message;

        if (strpos($message, '،')) {
            [$title, $start] = explode('،', $message, 2);
        }

        $parser = new DateOffsetParser;

        $start = $parser->resolveDateTimeFromText($start);
        $title = $parser->removeTimeFromText($title);

        if ($start === false) {
            return null;
        }

        return [
            'title' => $title,
            'start' => $start,
        ];
    }
}
