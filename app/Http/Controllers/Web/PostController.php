<?php

namespace App\Http\Controllers\Web;

use Knp\Snappy\Pdf;
use App\Excel\JsonToExcel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Post, Category};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        session()->forget('activeCategory');

        $posts = Post::query()->with(['user', 'media'])->orderBy('created_at', 'desc')->get();

        return view('index', compact('posts'));
    }

    public function edit($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.editPost', compact('post', 'categories'));
    }

    public function show($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with(['category', 'media'])->firstOrFail();

        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.singlePost')->with(compact('post', 'categories'));
    }

    public function store(Request $request)
    {
        $postData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->authUser->posts()->save($post = new Post($postData));

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        return redirect()->route('posts.index');
    }

    public function update(Request $request, $uuid)
    {
        $newPostData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        $post->update($newPostData);

        $post = Post::query()->where('uuid', $uuid)->first();
        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.editPost', compact('post', 'categories'))
            ->with(['updateMessage' => 'Post updated successfully']);
    }

    public function destroy($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $post->media->map(fn ($image) => $image->forceDelete());

        !isset($post->labels) ?: $post->labels()->detach($post->labels);

        $post->delete();

        return redirect()->back();
    }

    public function deletePostFile($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($post->media->count() == 0) {
            return back()->withErrors(['file' => 'post does not have file']);
        }

        $post->media->map(fn ($image) => $image->forceDelete());

        return redirect()->back();
    }

    public function filterPosts(Request $request)
    {
        $data = $request->validate([
            'from' => 'date',
            'to' => 'date'
        ]);

        $posts = Post::query()->whereBetWeen('created_at', [$data['from'], $data['to']])->latest()->get();

        return view('post.filterPosts', [
            ...compact('posts'),
            'from' => $data['from'],
            'to' => $data['to'],
        ]);
    }

    public function exportPosts(Request $request)
    {
        $data = $request->validate([
            'type' => Rule::in(['excel', 'pdf', 'word']),
            'from' => 'date',
            'to' => 'date'
        ]);

        $posts = Post::query()->whereBetWeen('created_at', [$data['from'], $data['to']])
            ->latest('title', 'body', 'created_at')->get()->map(function ($post) {
                $body = explode(" ", $post->body);

                return [
                    'title' => $post->title,
                    'body' => implode(" ", array_splice($body, 0, 10)) . ' ... ',
                    'created_at' => $post->created_at
                ];
            })->toArray();

        $filePath = match ($data['type']) {
            'pdf' => $this->pdfExport($posts),
            'excel' => $this->excelExport($posts),
            'word' => $this->wordExport($posts),
            default => 0,
        };

        return response()->download($filePath);
    }

    private function excelExport($posts)
    {
        $response = JsonToExcel::generate([
            'headers' => ['title', 'body', 'created at'],
            'title' => 'esml',
            'data' => $posts
        ]);

        return $response['data']['link'];
    }

    private function pdfExport($posts)
    {
        $snappy = new Pdf('wkhtmltopdf', [
            'encoding' => 'UTF-8',
            'page-size' => 'a4',
        ]);

        $filename = uniqid() . '.pdf';
        $snappy->generateFromHtml(
            view('pdf.posts-report')->with(compact(('posts')))->render(),
            'temp/' . $filename
        );

        return Storage::disk('public')->url($filename);
    }

    private function wordExport($posts)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();
        foreach ($posts as $post) {
            $section->addText(
                'Title: ' . $post['title'],
                array('name' => 'Tahoma', 'size' => 10)
            );

            $section->addLine();
            $section->addText(
                'Body: ' . $post['body'],
                array('name' => 'Tahoma', 'size' => 10)
            );

            $section->addLine();
            $section->addText(
                'Created at: ' . $post['created_at'],
                array('name' => 'Tahoma', 'size' => 10)
            );

            $section->addLine();
            $section->addLine();
            $section->addLine();
            $section->addLine();
            $section->addLine();
        }
        
        File::isDirectory('temp') or File::makeDirectory('temp', 0777, true, true);
        
        $filename = uniqid() . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('temp/' . $filename);

        return Storage::disk('public')->url($filename);
    }
}
