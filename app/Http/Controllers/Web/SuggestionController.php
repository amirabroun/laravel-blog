<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\Dataset;

class SuggestionController extends Controller
{
    use Dataset;

    public function search(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);
        
        $keywords = $this->extractKeywords($request->text, $this->stopWords());
        $keywords = [...$keywords, ...$this->addRelatedWordsToKeywords($keywords)];
        
        $query = Post::query()->with(['category', 'labels']);
        foreach ($keywords as $keyword) {
            $query->orWhereHas('labels', function ($query) use ($keyword) {
                $query->orWhere('title', $keyword);
            })
            ->orWhereHas('category', function ($query) use ($keyword) {
                $query->orWhere('title', $keyword);
            })
            ->orwhere(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('body', 'LIKE', "%{$keyword}%");
            });
        }

        $posts = $query->get()->sortByDesc('updated_at');

        $staticResponses = $this->staticResponses();
        $Responses = [];
        foreach ($keywords as $keyword) {
            if (array_key_exists($keyword, $staticResponses)) {
                $Responses[$keyword] = $staticResponses[$keyword];

                unset($staticResponses[$keyword]);
            }
        }

        $allStaticResponses = [];
        foreach ($Responses as $field => $responses) {
            foreach ($responses as $question => $response) {
                $userQuestions = $this->detectQuestionType($request->text);
        
                foreach ($userQuestions as $userQuestion) {
                    $allStaticResponses[$field][$this->compeleteQuestion($userQuestion, $field)] = $responses[$userQuestion];
                }
            }
        }

        return view('index', [
            'text' => $request->text,
            ...compact('posts', 'allStaticResponses')
        ]);
    }

    public function getSuggestionsPosts()
    {
        $postsByLabels = $this->getPostsByLikedLabels();
        $similarTitlePosts = $this->getPostsBySimilarTitle();

        $posts = $postsByLabels
            ->merge($similarTitlePosts)
            ->unique()->sortByDesc('created_at');

        return view('index', compact('posts'));
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

    private function getPostsQuery()
    {
        return Post::query()
            ->whereDoesntHave('user.followers', function ($query) {
                $query->where('follower_id', auth()->id());
            })
            ->with([
                'user' => fn($query) => $query->with('media'),
                'media',
                'labels'
            ])
            ->take(mt_rand(2, 4));
    }
}
