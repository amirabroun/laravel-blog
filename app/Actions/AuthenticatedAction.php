<?php

namespace App\Actions;

class AuthenticatedAction
{
    public function handle($message, $telegramUserId)
    {
        if (isCommandMatched($message, $this->taskKeyWords())) {
            return $this->createTask($message, $telegramUserId);
        }

        if (isCommandMatched($message, $this->deleteKeyWords())) {
            return $this->deleteTask($message, $telegramUserId);
        }

        if (isCommandMatched($message, $this->editKeyWords())) {
            return $this->editTask($message, $telegramUserId);
        }

        if (isCommandMatched($message, $this->logoutKeyWords())) {
            return $this->logout($telegramUserId);
        }

        return 'اوه! متاسفم، نتونستم دقیقا متوجه بشم که چی می‌خواهید. ' . PHP_EOL .
            'لطفا بیشتر توضیح بدید تا بهتر بتونم کمک کنم. 😊';
    }

    private function createTask($message, $telegramUserId)
    {
        return 'در حال ساخت تسک جدید...';
    }

    private function deleteTask($message, $telegramUserId)
    {
        return 'در حال حذف تسک...';
    }

    private function editTask($message, $telegramUserId)
    {
        return 'در حال ویرایش تسک...';
    }

    private function logout($telegramUserId)
    {
        telegramUserState($telegramUserId, null);
        telegramCache($telegramUserId, null);

        return 'شما از حساب خود خارج شدید.';
    }

    private function taskKeyWords()
    {
        return [
            'ساخت تسک',
            'create task',
            'اضافه کردن تسک',
            'add task',
            'ایجاد تسک',
            'create a task',
            'create new task',
        ];
    }

    private function deleteKeyWords()
    {
        return [
            'دلیت',
            'delete',
            'حذف تسک',
            'remove task',
            'پاک کردن تسک',
            'delete task',
            'از بین بردن تسک',
        ];
    }

    private function editKeyWords()
    {
        return [
            'میخوام تسک ادیت کنم',
            'edit task',
            'ویرایش تسک',
            'تغییر تسک',
            'edit the task',
            'update task',
            'بروزرسانی تسک',
        ];
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
