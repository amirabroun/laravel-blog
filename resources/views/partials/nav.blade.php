<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <span>
        <a class="nav-link text-info" href=" {{ route('blog') }} ">
            blog
        </a>
    </span>
    <span>
        |
    </span>
    <span class="ml-3">
        @auth Welcome&nbsp;
        <a class="text-dark" href=" {{ route('profile', ['id' => auth()->user()->id ]) }} ">
            {{ Auth::user()->first_name ?? 'friend' }}
        </a> !
        @endauth
    </span>
    <div class="collapse navbar-collapse ml-0" id="navbarSupportedContent">
        <ul class="navbar-nav">
            @auth
            <li class="nav-item ml-2">
                <a class="nav-link text-dark" href="/users">Users</a>
            </li>
            @endauth

            @if (auth()->user()?->isAdmin())
            <li class="nav-item">
                <a class="nav-link text-dark" href="/posts">New Post</a>
            </li>
            @endif

            @auth
            <li class="nav-item">
                <a class="nav-link text-danger" href="/log-out">Exit</a>
            </li>
            @endauth

            @guest
            <li class="nav-item">
                <a class="nav-link text-info" href="/login">Login</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/register">Register</a>
            </li>
            @endguest

        </ul>
    </div>
</nav>