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

        body {
            font-family: "Poppins", sans-serif;
        }

        .main-section .card {
            margin-bottom: 1.5rem !important;
        }

        .footer-section {
            background: #E8CBC0;
            background: linear-gradient(to right, #636FA4, #E8CBC0);
            padding-top: 60px;
            padding-bottom: 60px;
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
            @auth Welcome {{ Auth::user()->name }}! @endauth
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
                
                <li class="nav-item">
                    <a class="nav-link text-info" href="/posts">New Post</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="/log-out">Exit</a>
                </li>
                @endauth

                @guest
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                @endguest

            </ul>
        </div>
    </nav>
    <!-- navbar ends -->
    <!-- main content start  -->
    <div class="container mt-5">
        <div class="row main-section">
            <div class="col-sm-12 col-md-9 col-lg-9">
                @isset($posts)
                @foreach ($posts as $post)
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header">
                        <form action="{{ route('post.delete') }}" method="POST">
                            <span>By</span>
                            <span class="text-info"> {{ $post->user()->first()->name }} </span>
                            @isset($post->created_at)
                            <span>On</span>
                            <span class="text-success"> {{ $post->created_at }} </span>
                            @endisset

                            @auth
                            <input type="hidden" name="_method" value="DELETE" />
                            <input type="hidden" name="id" value="{{ $post->id }}" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input class="btn text-danger ml-3" type="submit" value="Delete">
                            @endauth

                        </form>
                    </div>
                    <div class="card-body">
                        @isset($post->image_url)
                        <img class="card-img-top" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="bootstrap simple blog">
                        <hr>
                        @endisset
                        <h2 class="card-title">{{ $post->title }} </h2>
                        <p class="card-text">{{ $post->body }} </p>
                    </div>
                </div>
                @endforeach

                <div class="d-flex justify-content-center">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active">
                                <span class="page-link">
                                    2
                                    <span class="sr-only">(current)</span>
                                </span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endisset
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header">
                        Category
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#">Social</a></li>
                        <li class="list-group-item"><a href="#">Sports</a></li>
                        <li class="list-group-item"><a href="#">Technology</a></li>
                        <li class="list-group-item"><a href="#">Trend's</a></li>
                        <li class="list-group-item"><a href="#">Samsung</a></li>
                    </ul>
                    <div class="card-footer">
                        <span class="text-info"> Ads will be goes here</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main content ends -->
    <div style="bottom:0px; height:50px; width:100%;" class="footer-section mt-4">
        <p class="text-center mb-4 text-white">Written by Amir Abroun</p>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>