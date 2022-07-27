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
        @import url(https://fonts.googleapis.com/css?family=Poppins);

        body,
        html {
            font-family: "Poppins", sans-serif;
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
    <!-- navbar start -->
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
    <!-- navbar ends -->
    <!-- main content start  -->
    <div class="container-fuild m-5">
        <div class="row main-section">
            <div class="col-sm-12 col-md-12 col-lg-9 mb-4">
                @isset($posts)
                @foreach ($posts as $post)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <form action="{{ route('post.delete') }}" method="POST">
                            <span>By</span>
                            <span class="text-info">
                                <a class=" text-info" href="{{ route('profile', ['id' => $post->user->id]) }}">
                                    {{ $post->user()->first()->first_name }}
                                </a>
                            </span>
                            @isset($post->created_at)
                            <span>On</span>
                            <span class="text-success"> {{ $post->created_at }} </span>
                            @endisset

                            @if (auth()->user()?->isAdmin())
                            <input type="hidden" name="_method" value="DELETE" />
                            <input type="hidden" name="id" value="{{ $post->id }}" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input class="btn text-danger ml-3" type="submit" value="Delete">
                            @endif

                        </form>
                    </div>
                    <div class="card-body">
                        @isset($post->image_url)
                        <img class="card-img-top" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="bootstrap simple blog">
                        <hr>
                        @endisset
                        <h2 class="card-title">{{ $post->title }} </h2>
                        <p class="card-text">{{ $post->body }}
                            <a href="{{ route('post.show', ['id' => $post->id]) }}">
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
                    <div class="card-header">
                        Category
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            # <a class="text-dark" href="/">
                                All
                            </a>
                        </li>

                        @isset($categories)
                        @foreach ($categories as $category)

                        <li class="list-group-item">
                            # <a class=" @isset($activeCategory)
                             @if ($activeCategory == $category->id) {{ 'text-danger' }}
                            @else {{ 'text-dark'  }} @endif @endisset" href="{{ route('blog.filter.category', ['category_title' => $category->title]) }}">
                                {{ $category->title }}
                            </a>
                            <span>
                                <a href="{{ route('category.show', ['title' => $category->title]) }}" class="ml-2 btn-sm btn-light text-info" href="#">
                                    info
                                </a>
                            </span>
                        </li>

                        @endforeach
                        @endisset
                    </ul>

                    @if (auth()->user()?->isAdmin())
                    <div class="card-footer">
                        <span>
                            <a class="text-success" href="#">
                                Add category
                            </a>
                        </span>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- main content ends -->
    <div style="bottom:0px; height:30px; width:100%;" class="footer-section">
        <p class="text-center mb-4 text-white">Written by Amir Abroun</p>
    </div>
</body>

</html>