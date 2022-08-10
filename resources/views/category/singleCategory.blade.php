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
                    <a class="text-danger" href="/">Back to Blog</a>
                    <hr>
                    <div class="text-content text-dark mb-1">
                        <h2 class="mt-2"> {{ $category->title }} </h2>
                        <p>{{ $category->description }}</p>
                        <div class="tags-share mb-1">
                            <div class="row">
                                <div class="col-8">
                                    <br>
                                    <a class=" text-info" href="{{ route('categories.posts.index', ['category_title' => $category->title]) }}">
                                        Posts in this category
                                    </a>
                                    <br>
                                    <hr>
                                    {{ $category->created_at }}
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