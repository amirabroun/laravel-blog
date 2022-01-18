@extends('layouts.app')

<body>

    <div class="container">
        <div class="jumbotron p-3 p-md-5 text-white rounded bg-dark mt-4">
            <div class="col-md-6 px-0">
                <h1 class="display-4 font-italic">{{ $author->name }}</h1>
                <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and
                    efficiently about what's most interesting in this post's contents.</p>
                <p class="lead mb-0"><a href="#" class="text-white font-weight-bold">Continue reading...</a></p>
            </div>
        </div>
    </div>

    <main role="main" class="container">
        <div class="row">
            <div class="col-md-8 blog-main">
                <div class="blog-post mt-4">
                    <h2 class="blog-post-title">{{ $author->email }}</h2>
                    <p class="blog-post-meta">{{ date_format($author->created_at, 'M d, Y') }} 
                         by <a href="#">{{ $author->name }}</a></p>
                    <hr>
                    <p>body</p>
                </div>
                <nav class="blog-pagination">
                    <a class="btn btn-outline-primary" href="#">Edit</a>
                    <a class="btn btn-outline-danger" href="#">Delete</a>
                </nav>
            </div>
        </div>
    </main>
</body>