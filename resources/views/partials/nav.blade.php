<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <span class="navbar-brand">
        @auth Welcome&nbsp;
        <a class="text-info" href=" {{ route('profile', ['id' => auth()->user()->id ]) }} ">
            {{ Auth::user()->first_name ?? 'friend' }}!
        </a>
        @endauth
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <span class="sr-only">(current)</span>
            </li>

            @auth
            <li class="nav-item">
                <a class="nav-link text-dark" href="/users">Users</a>
            </li>
            @endauth

            @if (auth()->user()?->isAdmin())
            <li class="nav-item">
                <a class="nav-link text-info" href="/posts">New Post</a>
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