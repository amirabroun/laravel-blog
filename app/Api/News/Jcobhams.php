<?php

namespace App\Api\News;

use jcobhams\NewsApi\NewsApi;

class Jcobhams
{
    private NewsApi $api;

    public function __construct()
    {
        $this->api = new NewsApi(config('services.api_news.api_key'));
    }

    /**
     * Fetches news articles based on a search query, formats them, and returns the result.
     *
     * @param string $search The search keyword for fetching news articles. Defaults to 'technology'.
     * @return \Illuminate\Support\Collection A collection of formatted news articles, each containing:
     *               - 'title': The title of the article.
     *               - 'body': The content of the article.
     *               - 'image': The URL of the article's image.
     *               - 'created_at': The publication date of the article.
     */
    public function getNews($limit = 10, $search = null)
    {
        $news = $this->api->getEverything(
            $search ?? array_rand($this->api->getCategories())
        );

        return collect($news->articles)->map(fn($article) => [
            'title' => $article->title,
            'body' => $article->description . '\n To see more: ' . $article->url,
            'image' => $article->urlToImage,
            'created_at' => $article->publishedAt,
        ])->take($limit);
    }
}
