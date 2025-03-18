<?php

namespace App\Actions;

use App\Models\User;

class HandleLoginAction
{
    public function __construct(private $telegramUserId, private $message = '')
    {
        //
    }

    public function handle()
    {
        $state = telegramUserState($this->telegramUserId);

        return match ($state) {
            'waiting_for_username' => $this->username(),
            'waiting_for_password' => $this->password(),
            default => $this->first(),
        };
    }

    private function first()
    {
        telegramUserState($this->telegramUserId, 'waiting_for_username');

        return 'سلام، برای دسترسی به امکانات لطفا لاگین کنید. یوزرنیم خود را وارد کنید.';
    }

    private function username()
    {
        $user = User::query()->where('username', $this->message)->first();

        if (!$user) {
            return 'یوزرنیم نامعتبر است. لطفاً دوباره امتحان کنید.';
        }

        telegramUserState($this->telegramUserId, 'waiting_for_password');
        telegramCache($this->telegramUserId, $this->message);

        return 'یوزرنیم شما درست است. رمز عبور را وارد کنید.';
    }

    private function password()
    {
        $isAuthenticated = auth()->attempt([
            'username' => telegramCache($this->telegramUserId),
            'password' => $this->message,
        ]);

        if (!$isAuthenticated) {
            telegramUserState($this->telegramUserId, 'waiting_for_username');

            return 'رمز عبور نامعتبر است. لطفاً دوباره یوزرنیم را وارد کنید.';
        }

        telegramUserState($this->telegramUserId, null);
        telegramAuthUser(
            $this->telegramUserId,
            User::query()->where('username', telegramCache($this->telegramUserId))->first()->id
        );

        return __('telegram.login_success', [], 'fa');
    }

    public function logout()
    {
        telegramUserState($this->telegramUserId, 'waiting_for_username');
        telegramAuthUser($this->telegramUserId, null);
        auth()->logout();

        return __('telegram.logged_out', [], 'fa');
    }
}
