<?php

namespace App\Actions;

use App\Models\Task;
use App\Helper\DateOffsetParser;
use App\Models\UnknownMessage;

class AuthenticatedAction
{
    public function __construct(private $telegramUserId, private $message = '', private $callbackData = '', private $replyMesage = '')
    {
        //
    }

    /**
     * @return array<message,reply_markup>
     */
    public function handle()
    {
        if ($this->callbackData == 'get_tasks') {
            return $this->getTasks();
        }

        if ($this->callbackData == 'logout') {
            return (new HandleLoginAction($this->telegramUserId))->logout();
        }

        if (str_starts_with($this->callbackData, 'task_creation_misunderstood_')) {
            return $this->fixMisunderstoodTask();
        }

        if (strpos($this->message, 'donetask') === 1) {
            return $this->deleteTask();
        }

        if ($parsed = $this->parseTaskMessage()) {
            return $this->saveTask($parsed);
        }

        auth()->user()->unknownMessages()->save(new UnknownMessage([
            'message' => $this->message
        ]));

        return $this->saveUnknownMessage();
    }

    private function saveUnknownMessage()
    {
        auth()->user()->unknownMessages()->save(new UnknownMessage([
            'message' => $this->message
        ]));

        return __('telegram.error_unknown', [], 'fa');
    }

    private function fixMisunderstoodTask()
    {
        $uuid = substr($this->callbackData, strlen('task_creation_misunderstood_'));

        auth()->user()->tasks()->where('uuid', $uuid)->delete();

        auth()->user()->unknownMessages()->save(new UnknownMessage([
            'message' => $this->replyMesage
        ]));

        return 'تسکی که اشتباهی داشتی پاک شد و اشتباهمون ذخیره شد تا بررسی بشه.';
    }

    private function saveTask($parsed)
    {
        $task = auth()->user()->tasks()->save(new Task([
            'title' => $parsed['title'],
            'start' => $parsed['start'],
        ]));

        $message = __('telegram.task_saved', ['title' => $this->getTaskTitle($task->title, $task->start)], 'fa');
        $replyMarkup = [
            'text' => 'اگر اشتباه شد کلیک کن😬',
            'callback_data' => 'task_creation_misunderstood_' . $task->uuid
        ];

        return [$message, $replyMarkup];
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

    private function deleteTask()
    {
        $taskId = str_replace('/donetask', '', $this->message);

        auth()->user()->tasks()->where('id', $taskId)->delete();

        return $this->getTasks(__('telegram.task_deleted', [], 'fa'));
    }

    private function parseTaskMessage()
    {
        $title = $this->message;
        $start = $this->message;

        if (strpos($this->message, '،')) {
            [$title, $start] = explode('،', $this->message, 2);
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
