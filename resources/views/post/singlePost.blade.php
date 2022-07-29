<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Kanit:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">
    <title>{{ $post->title }}</title>

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
            font-weight: 200;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #414141;
            padding-bottom: 25px;
            display: inline-block;
            width: 100%;
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
    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <img src="https://laravel.com/images/partners.png" alt="">
                    <hr>
                    <div class="text-content text-dark">
                        <a class="text-info" href="{{ route('category.show', ['title' => $post->category->title]) }}">
                            {{ $post->category->title }}
                        </a> / {{ $post->title }}
                        <p> {{ $post->body }}
                            <br>
                            <br>
                            <a class=" text-info" href="{{ route('profile', ['id' => $post->user->id]) }}">
                                {{ $post->user->first_name . ' ' . $post->user->last_name }}
                            </a> on {{ $post->created_at }}
                            <br>
                        </p>
                        <a class="text-danger" href="/">Back to Blog</a>
                        <br>
                        <br>

                        @isset($post->labels)
                        <div class="tags-share">
                            <div class="row">
                                <div class="col">
                                    lables :
                                    @foreach ($post->labels as $label)
                                    <a class="bg-light text-dark  rounded mr-1 mt-3" href="#"> {{ $label->title }} </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="bottom:0px; height:30px; width:100%;" class="footer-section mt-4">
        <p class="text-center mb-4 text-white">Written by Amir Abroun</p>
    </div>
</body>

</html>