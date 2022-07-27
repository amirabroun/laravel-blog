<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Kanit:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">
    <title>Category {{ $category->title }}</title>
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

        .footer-section {
            position: fixed;
            background: #E8CBC0;
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
            padding-top: 25px;
            padding-bottom: 50px;
            margin-top: 400px;
        }
    </style>
</head>

<body>
    <div class="container mt-4" style="margin-bottom: 100px;">
        <div class="col-md-10 ">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <div class="text-content text-dark mb-4">
                        <h2 class="mt-4"> {{ $category->title }} </h2>
                        <p>{{ $category->description }}</p>
                        <div class="tags-share mb-3">
                            <div class="row">
                                <div class="col-8">
                                    {{ $category->created_at }}
                                </div>
                            </div>
                        </div>
                        <a class="text-danger" href="/">Back to Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="bottom:0px; height:50px; width:100%;" class="footer-section">
        <p class="text-center mb-4 text-white">Written by Amir Abroun</p>
    </div>
</body>

</html>