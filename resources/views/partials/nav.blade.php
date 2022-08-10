<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <span class="mt-2 mr-2 mb-2">
        <a class="text-dark mr-2" href=" {{ route('posts.index') }} ">
            blog
        </a>
        |

        @auth
        <span class="mb-0 ml-2 mr-3">
            Welcome
            <a class="text-dark" href=" {{ route('users.profile.show', ['id' => auth()->user()->id ]) }} ">
                {{ Auth::user()->first_name ?? 'friend' }}
            </a> !
        </span>
        @endauth

        @guest
        <span class="">
            <a class="text-info ml-2" href="{{ route('register.index') }}">Register</a>
            <a class="text-success ml-2" href="{{ route('login.index') }}">Login</a>
        </span>
        @endguest
    </span>

    @auth

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item d-block  d-lg-none">
                <h1>
                    <hr>
                </h1>
            </li>

            <li class="nav-item">
                <a class="mr-3 text-dark" href="{{ route('users.index') }}">Users</a>
            </li>

            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>

            @if (auth()->user()?->isAdmin())
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
                <a class="mr-3 text-danger" href="{{ route('log-out') }}">Exit</a>
            </li>
            <li class="nav-item d-block  d-lg-none">
                <h1></h1>
            </li>
            @endauth
        </ul>
    </div>
    @endauth
</nav>