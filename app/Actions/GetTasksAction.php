<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class GetTasksAction
{
    public function __invoke($header = '')
    {
        $user = auth()->user();

        if ($user->tasks()->count() == 0) {
            return $header . PHP_EOL . PHP_EOL . __('telegram.no_tasks', [], 'fa');
        }

        $weekStart = now()->startOfWeek(6);
        $weekEnd = now()->endOfWeek(5);

        $nextWeekStart = $weekStart->copy()->addWeek();
        $nextWeekEnd = $nextWeekStart->copy()->endOfWeek(5);
        $nextWeekHeader = PHP_EOL . '📅 *برنامه‌های هفته‌های بعد*' . PHP_EOL;

        return trim(
            $header . PHP_EOL . PHP_EOL .
                $this->formatWeekTasks($weekStart, $weekEnd) .
                $this->formatWeekTasks($nextWeekStart, $nextWeekEnd, $nextWeekHeader)
        );
    }

    private function getWeekTasks(Carbon $start, Carbon $end): array
    {
        $tasksByDay = auth()->user()->tasks()
            ->latest('start')
            ->whereBetween('start', [$start, $end])
            ->get()
            ->groupBy(fn($task) => \Carbon\Carbon::parse($task->start)->dayOfWeek);

        $output = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $taskList = $tasksByDay->get($date->dayOfWeek, collect());


            if (!$this->shouldShowDay($date, $taskList)) {
                continue;
            }

            $output[] = [
                'header' => $this->formatDayHeader($date),
                'tasks' => $this->formatTasksForDay($taskList),
            ];
        }

        return $output;
    }

    private function formatWeekTasks(Carbon $start, Carbon $end, string $header = ''): string
    {
        $tasks = $this->getWeekTasks($start, $end);

        if (empty($tasks)) {
            return '';
        }

        $output = [];
        foreach ($tasks as $task) {
            $output[] = $task['header'];
            $output[] = $task['tasks'];
            $output[] = PHP_EOL;
        }

        $result = implode(PHP_EOL, $output);
        if ($header) {
            return PHP_EOL . $header . PHP_EOL . $result;
        }

        return $result;
    }

    private function getTaskTitle($task)
    {
        $time = $task->start ? toJalali($task->start, 'H:i') : '';
        $title = "*{$task->title}*";

        return trim("$time • $title") . "  ← /done_task_{$task->id}";
    }

    private function formatDayHeader($date)
    {
        $formattedDate = toJalali($date, 'l، d %B');

        return $date->isToday() ? "📍 *امروز - $formattedDate*" : "🏷 *$formattedDate*";
    }

    private function formatTasksForDay($taskList)
    {
        if ($taskList->isEmpty()) {
            return  "🛌 (هیچ کاری نیست)";
        }

        return $taskList->map(fn($task) => $this->getTaskTitle($task))->implode(PHP_EOL);
    }

    /**
     * - For the current week: Hide past days but show future days, even if empty.
     * - For the next week: Show only days that have tasks.
     */
    private function shouldShowDay(Carbon $date, Collection $taskList): bool
    {
        if ($date->isToday()) {
            return true;
        }

        $isCurrentWeek = now()->startOfWeek(6)->isSameWeek(now());

        if ($isCurrentWeek) {
            return $taskList->isNotEmpty() || $date->gte(now()->startOfDay());
        }

        return $taskList->isNotEmpty();
    }
}
