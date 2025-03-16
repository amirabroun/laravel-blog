<?php

namespace App\Actions;

use App\Models\User;

class HandleLoginAction
{
    public function handle($telegramUserId, $message)
    {
        $state = telegramUserState($telegramUserId);

        return match ($state) {
            'waiting_for_username' => $this->username($telegramUserId, $message),
            'waiting_for_password' => $this->password($telegramUserId, $message),
            default => $this->first($telegramUserId),
        };
    }

    private function first($telegramUserId)
    {
        telegramUserState($telegramUserId, 'waiting_for_username');

        return 'سلام، برای دسترسی به امکانات لطفا لاگین کنید. یوزرنیم خود را وارد کنید.';
    }

    private function username($telegramUserId, $username)
    {
        $user = User::query()->where('username', $username)->first();

        if (!$user) {
            return 'یوزرنیم نامعتبر است. لطفاً دوباره امتحان کنید.';
        }

        telegramUserState($telegramUserId, 'waiting_for_password');
        telegramCache($telegramUserId, $username);

        return 'یوزرنیم شما درست است. رمز عبور را وارد کنید.';
    }

    private function password($telegramUserId, $password)
    {
        $isAuthenticated = auth()->attempt([
            'username' => telegramCache($telegramUserId),
            'password' => $password,
        ]);

        if (!$isAuthenticated) {
            telegramUserState($telegramUserId, 'waiting_for_username');

            return 'رمز عبور نامعتبر است. لطفاً دوباره یوزرنیم را وارد کنید.';
        }

        telegramUserState($telegramUserId, null);
        telegramAuthUser(
            $telegramUserId,
            User::query()->where('username', telegramCache($telegramUserId))->first()->id
        );

        return 'شما با موفقیت لاگین شدید! خوش آمدید، حالا من میفهمم چی میخواید. 😊' . PHP_EOL . PHP_EOL .
            'نحوه ساخت تسک: [اسم تسک]، [تاریخ تسک (ما میفهمیم هرجور بگی)]' . PHP_EOL . PHP_EOL .
            'چطور میتونم به شما کمک کنم؟';
    }
}
