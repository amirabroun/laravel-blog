<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Blog</title>
    <style>
        body,
        html {
            font-family: 'Kanit', sans-serif;
            height: 100%;
            min-height: 100%;
            overflow-x: hidden;
            background-color: #FFDEE9;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        .main-section .card {
            margin-bottom: 1.5rem !important;
        }

        .footer-section {
            position: fixed;
            background: #E8CBC0;
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
            padding-top: 25px;
            padding-bottom: 50px;
            margin-top: 4000px;
        }

        .comment-section span {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    @include('partials.nav')

    <div class="container-fuild m-5">
        <div class="row main-section">
            <div class="col-sm-12 col-md-12 col-lg-9 mb-4">
                @isset($posts)
                @foreach ($posts as $post)
                <div class="card shadow-sm">
                    @if (auth()->user()?->isAdmin())
                    <div class="card-header">
                        <form action="{{ route('posts.destroy', ['id' => $post->id]) }}" method="POST">
                            <span>By</span>
                            <span class="text-info">
                                <a class=" text-info" href="{{ route('users.profile.show', ['id' => $post->user->id]) }}">
                                    {{ $post->user()->first()->first_name }}
                                </a>
                            </span>

                            @isset($post->created_at)
                            <span>On</span>
                            <span class="text-success"> {{ $post->created_at }} </span>
                            @endisset

                            @if (auth()->user()?->isAdmin())
                            @method('DELETE')
                            @csrf
                            <input class="btn text-danger ml-3" type="submit" value="Delete">
                            @endif
                        </form>
                    </div>
                    @endif
                    <div class="card-body">
                        @isset($post->image_url)
                        <img class="card-img-top" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="bootstrap simple blog">
                        <hr>
                        @endisset
                        <h2 class="card-title">{{ $post->title }} </h2>
                        <p class="card-text">{{ $post->body }}
                            <a href="{{ route('posts.show', ['id' => $post->id]) }}">
                                more ...
                            </a>
                        </p>
                    </div>
                </div>
                @endforeach
                @endisset
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3">
                <div class="card shadow-sm">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item {{ !Route::is('posts.index') ? : 'bg-light' }}">
                            # <a class="{{ Route::is('posts.index') ? 'text-info' : 'text-dark' }}" href="/">
                                All
                            </a>
                        </li>

                        @php $categories = \App\Models\Category::all() @endphp

                        @if(count($categories))
                        @foreach ($categories as $category)
                        <li class="list-group-item {{ (session('activeCategory') ?? null) == $category->id ? 'bg-light' : '' }}">
                            # <a class="{{ (session('activeCategory') ?? null) == $category->id ? 'text-info' : 'text-dark' }}
                            {{ Route::is('posts.index') ? 'text-dark' : 'text-info' }}" href="{{ route('categories.posts.index', ['category_title' => $category->title]) }}">
                                {{ $category->title }}
                            </a>
                            <span>
                                <a href="{{ route('categories.show', ['title' => $category->title]) }}" class="ml-2 btn-sm btn-light text-success" href="#">
                                    info
                                </a>
                            </span>
                        </li>
                        @endforeach
                        @endif

                    </ul>

                    @if (auth()->user()?->isAdmin())
                    <div class="card-footer">
                        <span>
                            <a class="text-success" href="{{ route('categories.create') }}">
                                Add category
                            </a>
                        </span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>