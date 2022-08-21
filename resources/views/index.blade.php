<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')
    <div class="col mt-3 mb-5">
        <div class="row main-section">
            <div class="col-lg-1"></div>
            <div class="col-sm-12 col-md-12 col-lg-7 mb-4 shadow-post">
                @isset($posts)
                @foreach ($posts as $post)
                <div class="card mb-4">
                    @if (auth()->user()?->isAdmin())
                    <div class="card-header text-muted" style="font-size: 15px;">
                        <span>
                            <a style="color: blue;" class="text-dark" href="{{ route('users.profile.show', ['id' => $post->user->id]) }}">
                                {{ $post->user()->first()->first_name . ' ' . $post->user()->first()->last_name }}
                            </a>
                        </span>
                        <span class="text-muted">
                            /
                        </span>
                        @isset($post->created_at)
                        <span class="text-dark mr-2">{{ date('F j, Y, g:i a', strtotime($post->created_at)) }} </span>
                        @endisset

                        @if (auth()->user()?->isAdmin())
                        <button class="toggler action-post-btn" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-post-{{ $post->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler text-dark">act</span>
                        </button>
                        <div class="collapse" id="navbarSupportedContent-post-{{ $post->id }}">
                            <ul class="navbar-nav bg-light">
                                <li class="nav-item">
                                    <h1>
                                        <hr>
                                    </h1>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="mr-3 text-info">
                                        edit
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <h1></h1>
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
                                {{ $post->title }}
                            </h2>
                            @if(File::exists(base_path('/public/image/') . $post->image_url) && isset($post->image_url))
                            <hr>
                            <img class="card-img-top w-100" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="#">
                            <br>
                            <br>
                            @endif
                            <p class="card-text">
                                {{ $post->body }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
                @endisset
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3 d-none d-lg-block d-xl-block">
                <div class="card shadow-sm mb-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item {{ !session('activeCategory') ? 'bg-light' : '' }}">
                            <a style="font-size: 23px;" href="{{ route('posts.index') }}" class="{{ !session('activeCategory') ? 'text-danger' : 'text-dark' }}">
                                #
                            </a>
                            <span class="text-dark" href="{{ route('posts.index') }}">
                                All
                            </span>
                        </li>

                        @php $categories = \App\Models\Category::all() @endphp

                        @if(count($categories))
                        @foreach ($categories as $category)
                        <li class="list-group-item {{ session('activeCategory') == $category->id ? 'bg-light' : '' }}">
                            <a style="font-size: 23px;" class="mr-2 {{ session('activeCategory') == $category->id ? 'text-danger' : 'text-dark' }}" href="{{ route('categories.posts.index', ['category_title' => $category->title]) }}">
                                #
                            </a>
                            <a class="text-dark" href="{{ route('categories.show', ['title' => $category->title]) }}">
                                {{ $category->title }}
                            </a>

                            <button class="toggler action-post-btn ml-2 p-1 btn-sm btn-light text-success" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-category-{{ $category->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler">act</span>
                            </button>
                            <div class="collapse" id="navbarSupportedContent-category-{{ $category->id }}">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <h1>
                                            <hr>
                                        </h1>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('categories.edit', ['id' => $category->id]) }}" class="mr-3 text-info">
                                            edit
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <h1></h1>
                                    </li>

                                    <li class="nav-item">
                                        <form action="{{ route('categories.destroy', ['id' => $category->id]) }}" method="POST" id="delete-form-{{ $category->id }}">
                                            @csrf @method('DELETE')
                                        </form>
                                        <a href="javascript:void(0)" class="mr-3 text-danger" onclick="$('#delete-form-{{ $category->id }}').submit()">
                                            Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
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