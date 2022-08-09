<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">
    <title> {{ $user->first_name . ' ' . $user->last_name }} </title>

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

        .single-blog-post img {
            width: 100%;
            overflow-y: hidden;
        }

        .single-blog-post .text-content span {
            font-size: 16px;
            display: inline-block;
            width: 80%;
        }

        .single-blog-post .text-content h2 {
            font-size: 36px;
            letter-spacing: 0.5px;
            text-transform: capitalize;
            font-weight: 300;
        }

        .single-blog-post .text-content p {
            font-size: 16px;
            font-weight: 300;
            line-height: 28px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .single-blog-post .tags-share {
            border-top: 1px solid #414141;
            border-bottom: 1px solid #414141;
            padding: 8px 0px 10px 0px;
        }

        .single-blog-post .tags-share ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .single-blog-post .tags-share ul li:first-child {
            color: #fff;
            font-size: 16px;
            font-weight: 200;
            letter-spacing: 0.5px;
        }

        .single-blog-post .tags-share ul li {
            display: inline-block;
            color: #fff;
        }

        .single-blog-post .tags-share ul li a {
            font-size: 16px;
            font-weight: 200;
            letter-spacing: 0.5px;
            color: #f4dd5b;
            text-decoration: none;
            transition: all 0.5s;
        }

        .single-blog-post .tags-share ul li a:hover {
            opacity: 0.5;
        }

        .single-blog-post .tags-share .share {
            text-align: right;
        }

        .sidebar-heding h2 {
            font-size: 20px;
            text-transform: capitalize;
            color: #fff;
            margin-top: 40px;
            letter-spacing: 0.5px;
            padding-bottom: 10px;
            border-bottom: 1px solid #414141;
            margin-bottom: 20px;
        }

        .footer-section {
            position: fixed;
            background: #E8CBC0;
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
            padding-top: 25px;
            padding-bottom: 50px;
            margin-top: 4000px;
        }
    </style>
</head>

<body>
    @include('partials.nav')

    @if ($updatePermission)
    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>
                    <hr>
                    <br>
                    <div class="text-content text-dark">
                        <form action="{{ route('users.profile.update', ['id' => $user->id]) }}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf

                            <div class=" row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">First Name</span>
                                    <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Last Name</span>
                                    <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">ID</span>
                                    <input type="text" name="student_number" value="{{ $user->student_number }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Email</span>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control mt-2 text-muted" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Soft Code</span>
                                    <input type="text" name="soft_code" value="{{ $user->soft_login_code }}" class="form-control mt-2 text-muted" />
                                </div>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-danger mt-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @isset($updateMessage)
                            <div class="alert alert-info mt-4">
                                <ul>
                                    <li>{{ $updateMessage }}</li>
                                </ul>
                            </div>
                            @endisset

                            <div class="form-field mt-5">
                                <button class="btn btn-info" type="submit">update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-sm-12 col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>
                    <hr>
                    <br>

                    <div class="row mt-4">
                        <div class="col-4 col-md-2">
                            <label>First Name : </label>
                        </div>
                        <div class="col-6 col-md-6">
                            <p> {{ $user->first_name }} </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-4 col-md-2">
                            <label>Last Name : </label>
                        </div>
                        <div class="col-6 col-md-6">
                            <p> {{ $user->last_name }} </p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-2 col-md-1">
                            <label>ID : </label>
                        </div>
                        <div class="col-6 col-md-10">
                            <p> {{ $user->student_number }} </p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-3 col-md-2">
                            <label>Email : </label>
                        </div>
                        <div class="col-9 col-md-10">
                            <p> {{ $user->email }} </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    @include('partials.footer')
</body>

</html>