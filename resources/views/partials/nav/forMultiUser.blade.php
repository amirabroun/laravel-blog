<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">

    <span class="mt-2 mr-2 mb-2">
        <a class="text-dark mr-2" href=" {{ route('posts.index') }} ">
            blog
        </a>
        |

        @auth
        <a class="text-dark mr-2" href=" {{ route('posts.index.suggestion') }} ">
            suggestion posts
        </a>
        @endauth

        @guest
        <span class="">
            <a class="text-info ml-2" href="{{ route('register.index') }}">Register</a>
            <a class="text-success ml-2" href="{{ route('login.index') }}">Login</a>
        </span>
        @endguest

        <form action="{{ route('posts.index.search') }}" method="GET" class="d-inline mr-1">
            <input
                type="text"
                name="text"
                value="{{ $text ?? '' }}"
                class="form-control d-inline-block"
                style="width: 200px; height: 30px;"
                placeholder="Search posts..."
                required>
            <button type="submit" style="width: 100px; height: 35px;" class="btn btn-info ml-1">Search</button>
        </form>
    </span>

    @auth
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-block  d-lg-none">
                <h1>
                    <hr>
                </h1>
            </li>

            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>

            @php $categories = \App\Models\Category::all(); @endphp
            @if (count($categories))
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            <li class="nav-item d-block d-lg-none">
                <a href="javascript:void(0)" class="mr-3 text-dark" onclick="$('#navbarSupportedContent-categories').submit()" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-categories" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    Categories
                </a>
            </li>

            <li class="nav-item d-block d-lg-none">
                <h1></h1>
            </li>

            <div class="collapse" id="navbarSupportedContent-categories">
                <ul class="navbar-nav">
                    <li class="{{ !session('activeCategory') ? 'bg-light' : '' }} ml-4">
                        <a style="font-size: 18px;" href="{{ route('posts.index') }}" class="{{ !session('activeCategory') ? 'text-danger' : 'text-dark' }} mb-2 mr-2">
                            #
                        </a>
                        <span class="text-dark mb-2" style="font-size: 15px;" href="{{ route('posts.index') }}">
                            All
                        </span>
                    </li>
                    <br class="mb-2">

                    @foreach ($categories as $category)
                    <li class="{{ session('activeCategory') == $category->id ? 'bg-light' : '' }} mb-2 ml-4">
                        <a style="font-size: 18px;" class="mr-2 {{ session('activeCategory') == $category->id ? 'text-danger' : 'text-dark' }}" href="{{ route('categories.posts', ['uuid' => $category->uuid]) }}">
                            #
                        </a>
                        <a class="text-dark" style="font-size: 15px;" href="{{ route('categories.posts', ['uuid' => $category->uuid]) }}">
                            {{ $category->title }}
                        </a>

                        <button class="toggler action-post-btn ml-2 p-1 btn-sm btn-dark text-light" style="font-size: 10px;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-category-{{ $category->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                                    <a href="{{ route('categories.edit', ['uuid' => $category->uuid]) }}" class="mr-3 text-info">
                                        edit
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <h1></h1>
                                </li>

                                <li class="nav-item">
                                    <form action="{{ route('categories.destroy', ['uuid' => $category->uuid]) }}" method="POST" id="delete-form-{{ $category->id }}">
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
            </div>

            @if (auth()->user())
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            <li class="nav-item">
                <a class="mr-3 text-dark" href="{{ route('posts.create') }}">New Post</a>
            </li>
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            @endif

            @auth
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            <li class="nav-item">
                <a class="mr-3 text-danger" href="{{ route('logout') }}">Exit</a>
            </li>
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            @endauth
        </ul>
    </div>
    @endauth
</nav>