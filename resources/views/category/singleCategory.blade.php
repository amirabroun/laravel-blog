<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Category: {{ $category->title }}</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 100px;">
        <div class="col-md-10 ">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <div class="text-content text-dark mb-1">
                        <h2 class="mt-2"> {{ $category->title }} </h2>
                        <br>
                        <p>{{ $category->description }}</p>
                        <div class="tags-share mb-1">
                            <div class="row">
                                <div class="col">
                                    <br>
                                    <span style="font-size: 13px;" class="text-muted">
                                        {{ date('F j, Y, g:i a', strtotime($category->created_at)) }}
                                    </span>
                                    <hr>
                                    
                                    @if (auth()->user()?->isAdmin())
                                    <a href="{{ route('categories.edit', ['id' => $category->id]) }}" style="font-size: 14px;" class="text-dark"> edit</a>
                                    /
                                    @endif

                                    <a class="text-dark" style="font-size: 14px;" href="{{ route('categories.posts.index', ['category_title' => $category->title]) }}">
                                        Posts in this category
                                    </a>
                                    /
                                    <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">Back to Blog</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>