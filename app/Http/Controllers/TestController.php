<?php

namespace App\Http\Controllers;

use App\Api\News\Jcobhams;
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
use Database\Seeders\BlogSeeder\GithubUsersSeeder;

class TestController
{
    public function __invoke(Request $request)
    {
        //
    }
}
