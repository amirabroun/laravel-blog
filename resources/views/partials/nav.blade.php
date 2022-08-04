<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <span class="mt-1 mr-2">
        <a class="text-info mr-2" href=" {{ route('posts.index') }} ">
            blog
        </a>

        |
        <span class="mr-2"></span>

        @auth Welcome&nbsp;
        <a class="text-dark" href=" {{ route('users.profile.show', ['id' => auth()->user()->id ]) }} ">
            {{ Auth::user()->first_name ?? 'friend' }}
        </a> !
        @endauth
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-1" id="navbarSupportedContent">
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('users.index') }}">Users</a>
            </li>
            @endauth

            @if (auth()->user()?->isAdmin())
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('posts.create') }}">New Post</a>
            </li>
            @endif

            @auth
            <li class="nav-item">
                <a class="nav-link text-danger" href="{{ route('log-out') }}">Exit</a>
            </li>
            @endauth

            @guest
            <li class="nav-item">
                <a class="nav-link text-info" href="{{ route('login.index') }}">Login</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('register.index') }}">Register</a>
            </li>
            @endguest

        </ul>
    </div>
</nav>