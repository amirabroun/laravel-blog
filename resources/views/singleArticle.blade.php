@extends('layouts.app')


<body class="shadow bg-white m-2">

    <div class="image text-center">
        <img src="{{ asset('image/' . $article->image_name ) }}" alt="{{ $article->image_name }}" class="w-25">
    </div>

    <main role="main" class="container mt-3">
        <div class="row">
            <div class="col-md-8 blog-main">
                <div class="blog-post mt-4">
                    <h2 class="blog-post-title">{{ $article->title }}</h2>
                    <p class="blog-post-meta">{{ date_format($article->created_at, 'M d, Y') }}
                        by <a class="m-variant" href="#">{{ $author->name }}</a></p>
                    <hr>
                    <p>{{ $article->body }}</p>
                </div>
                <nav class="blog-pagination">
                    <a class="btn btn-outline-primary" href="#">Edit</a>
                    <a class="btn btn-outline-danger" href="#">Delete</a>
                </nav>
            </div>
            <aside class="col-md-4 blog-sidebar">
                <div class="p-3 mb-3 bg-light rounded">
                    <h4 class="font-italic">About author</h4>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#">March 2014</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </main>
</body>