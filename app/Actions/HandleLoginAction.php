<?php

namespace App\Actions;

use App\Models\User;

class HandleLoginAction
{
    public function handle($message, $telegramUserId)
    {
        $state = telegramUserState($telegramUserId);

        return match ($state) {
            'waiting_for_username' => $this->username($message, $telegramUserId),
            'waiting_for_password' => $this->password($message, $telegramUserId),
            default => $this->first($telegramUserId),
        };
    }

    private function first($telegramUserId)
    {
        telegramUserState($telegramUserId, 'waiting_for_username');

        return 'سلام، برای دسترسی به امکانات لطفا لاگین کنید. یوزرنیم خود را وارد کنید.';
    }

    private function username($username, $telegramUserId)
    {
        $user = User::query()->where('username', $username)->first();

        if (!$user) {
            return 'یوزرنیم نامعتبر است. لطفاً دوباره امتحان کنید.';
        }

        telegramUserState($telegramUserId, 'waiting_for_password');
        telegramCache($telegramUserId, $username);

        return 'یوزرنیم شما درست است. رمز عبور را وارد کنید.';
    }

    private function password($password, $telegramUserId)
    {
        $isAuthenticated = auth()->validate([
            'username' => telegramCache($telegramUserId),
            'password' => $password,
        ]);

        if (!$isAuthenticated) {
            telegramUserState($telegramUserId, 'waiting_for_username');

            return 'رمز عبور نامعتبر است. لطفاً دوباره یوزرنیم را وارد کنید.';
        }

        telegramAuthUser(
            $telegramUserId,
            User::query()->where('username', telegramCache($telegramUserId))->first()->id
        );

        return 'شما با موفقیت لاگین شدید! خوش آمدید، حالا من میفهمم چی میخواید. 😊' . PHP_EOL . PHP_EOL .
            'شما می‌توانید تسک جدید بسازید، تسک‌های قبلی رو ویرایش کنید، یا حتی تسک‌ها رو حذف کنید. ' . PHP_EOL .
            'اگر به فکر لاگ اوت هم هستید، می‌تونید به راحتی من رو از حساب کاربریتون خارج کنید. 😎' . PHP_EOL . PHP_EOL .
            'چطور میتونم به شما کمک کنم؟';
    }
}
