<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post, User};
use App\Http\Resources\{PostResource, UserResource};

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $userSuggestionsQuery = User::query()->take(mt_rand(2, 5))->inRandomOrder()->with('media');

        !auth()->check() ?:
            $userSuggestionsQuery->whereNotIn('id', [auth()->id(), ...app()->auth_followings->pluck('id')->toArray()]);

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => UserResource::collection($userSuggestionsQuery->get())],
        ];
    }

    public function getSuggestionsPosts()
    {
        $postsByLabels = $this->getPostsByLikedLabels();
        $similarTitlePosts = $this->getPostsBySimilarTitle();

        $newPosts = $this->getNewPosts();
        $popularPosts = $this->getPopularPosts();

        $allSuggestedPosts = $postsByLabels
            ->merge($newPosts)
            ->merge($popularPosts)
            ->merge($similarTitlePosts)
            ->unique()
            ->sortByDesc('created_at');

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => ['posts' => PostResource::collection($allSuggestedPosts)],
        ];
    }

    private function getPostsByLikedLabels()
    {
        $likes = auth()->user()
            ->likes()
            ->where('likeable_type', Post::class)
            ->with('likeable', fn($query) => $query->with('labels'))
            ->get();

        $likedLabelIds = [];
        foreach ($likes as $like) {
            foreach ($like->likeable->labels as $lable) {
                $likedLabelIds[] = $lable->pivot->label_id;
            }
        }

        return $this->getPostsQuery()->whereHas(
            'labels',
            fn($query) => $query->whereIn('labels.id', $likedLabelIds)
        )->get();
    }

    private function getPostsBySimilarTitle()
    {
        $likedPostsTitle = auth()->user()->likes()
            ->where('likeable_type', Post::class)
            ->with('likeable')
            ->get()
            ->pluck('likeable.title', 'likeable.id');

        $similarTitles = [];
        foreach ($likedPostsTitle as $title) {
            foreach (explode(' ', $title) as $word) {
                $similarTitles[] = $word;
            }
        }

        $similarPosts = $this->getPostsQuery()
            ->whereNotIn('id', $likedPostsTitle->keys())
            ->where(function ($query) use ($similarTitles) {
                foreach (array_unique($similarTitles) as $title) {
                    $query->orwhere('title', 'like',  '%' . $title . '%');
                }
            })
            ->get();

        return $similarPosts;
    }

    private function getNewPosts()
    {
        return $this->getPostsQuery()
            ->where('created_at', '>=', now()->subDays(7))
            ->get();
    }

    private function getPopularPosts()
    {
        return $this->getPostsQuery()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->get();
    }

    private function getPostsQuery()
    {
        return Post::query()
            ->whereDoesntHave('user.followers', function ($query) {
                $query->where('follower_id', auth()->id());
            })
            ->with([
                'user' => fn($query) => $query->with('media'),
                'media', 'labels'
            ])
            ->whereNot('user_id', auth()->id())
            ->take(mt_rand(2, 4));
    }
}
