<?php

namespace App\Console\Commands;

use App\Api\News\Jcobhams;
use App\Jobs\SaveSomeNewsForUserJob;
use App\Models\User;
use Illuminate\Console\Command;

class AddNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-news {count_of_user_selection=10} {number_of_news_for_each_user=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Addd some news randomly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!config('services.api_news.api_key')) {
            return;
        }

        $newsArticles = (new Jcobhams)->getNews(
            $this->argument('number_of_news_for_each_user') * $this->argument('count_of_user_selection')
        );

        $users = User::query()
            ->withCount('posts')
            ->orderBy('posts_count', 'asc')
            ->take($this->argument('count_of_user_selection'))
            ->get();

        $newsChunks = $newsArticles->chunk($this->argument('number_of_news_for_each_user'));

        $users->each(function ($user, $index) use ($newsChunks) {
            if (!isset($newsChunks[$index])) return;

            dispatch(new SaveSomeNewsForUserJob($user, $newsChunks[$index]));
        });
    }
}
