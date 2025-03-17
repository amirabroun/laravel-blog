<?php

namespace App\Http\Controllers;

use App\Api\News\Jcobhams;
use App\Helper\DateOffsetParser;
use App\Http\Controllers\Api\UserController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Support\Facades\DB;


use Closure;
use Illuminate\Support\Collection;
use App\Http\Resources\{PostResource, UserResource};
use App\Models\Label;
use App\Models\Task;
use Database\Seeders\BlogSeeder\GithubUsersSeeder;
use Telegram\Bot\Laravel\Facades\Telegram;

class TestController
{
    public function __invoke()
    {
        // $this->telegramWebhook();
    }

    private function telegramWebhook()
    {
        $ngrokUrl = 'https://978d-51-112-83-198.ngrok-free.app';
        $botToken = config('telegram.bots.mybot.token');

        $url = "$ngrokUrl/api/telegram/inputs/$botToken";

        $apiUrl = "https://api.telegram.org/bot$botToken/setWebhook?url=" . $url;

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);


        if ($response === false) {
            dd('cURL error: ' . curl_error($ch));
        } else {
            dd('Response: ' . $response);
        }

        curl_close($ch);
    }
}
