<?php

namespace App\Jobs;

use App\Api\News\Jcobhams;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveSomeNewsForUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private User $user, private $news) {}

    public function handle(): void
    {
        foreach ($this->news as $article) {
            $this->saveNewsArticleAsPost($article);
        }
    }

    private function saveNewsArticleAsPost(array $article)
    {
        if (Post::query()->where('title', $article['title'])->first() != null) {
            return;
        }

        $post = $this->user->posts()->create([
            'title' => $article['title'],
            'body' => $article['body'],
            'created_at' => $article['created_at'],
        ]);

        if ($article['image']) {
            $post->addMediaFromUrl($article['image'])
                ->usingFileName(uniqid('external_file'))
                ->toMediaCollection('image');
        }
    }
}
