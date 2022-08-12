<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog</title>

    @include('partials.abstract-css')
    <style>
        .action-post-btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
</head>

<body>
    @include('partials.nav')
    <div class="col mt-3 mb-5">
        <div class="row main-section">
            <div class="col-lg-1"></div>
            <div class="col-sm-12 col-md-12 col-lg-7 mb-4">
                @isset($posts)
                @foreach ($posts as $post)
                <div class="card shadow-sm mb-4">
                    @if (auth()->user()?->isAdmin())
                    <div class="card-header" style="font-size: 15px;">
                        <span>By</span>
                        <span class="text-info">
                            <a class=" text-info" href="{{ route('users.profile.show', ['id' => $post->user->id]) }}">
                                {{ $post->user()->first()->first_name }}
                            </a>
                        </span>

                        @isset($post->created_at)
                        <span>On</span>
                        <span class="text-success mr-1">{{ date('m/d h:m', strtotime($post->created_at)) }} </span>
                        @endisset

                        @if (auth()->user()?->isAdmin())
                        <button class="toggler action-post-btn" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-post-{{ $post->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler">. . .</span>
                        </button>
                        <div class="collapse" id="navbarSupportedContent-post-{{ $post->id }}">
                            <ul class="navbar-nav bg-light">
                                <li class="nav-item">
                                    <h1>
                                        <hr>
                                    </h1>
                                </li>

                                <li class="nav-item">
                                    <form action="{{ route('posts.destroy', ['id' => $post->id]) }}" method="POST" id="delete-form-{{ $post->id }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <a href="javascript:void(0)" class="mr-3 text-danger" onclick="$('#delete-form-{{ $post->id }}').submit()">
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endif
                    <a class="text-dark" style="text-decoration: none;" href="{{ route('posts.show', ['id' => $post->id]) }}">
                        <div class="card-body">
                            <h2 class="card-title">
                                # {{ $post->title }}
                            </h2>
                            @isset($post->image_url)
                            <br>
                            <hr>
                            <img class="card-img-top w-100" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="bootstrap simple blog">
                            <br>
                            <br>
                            @endisset
                            <p class="card-text">
                                {{ $post->body }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
                @endisset
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3 mb-4">
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
            <div class="col-lg-1"></div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>