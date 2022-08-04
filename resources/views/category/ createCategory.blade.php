<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">
    <title>Create Category</title>

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
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('categories.store') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label for="title" class="col-form-label">title :</label>
                                    <input type="text" class="form-control mt-2" name="title" id="name" placeholder="title">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 mt-4">
                                    <label for="description" class="col-form-label">description :</label>
                                    <textarea class="form-control mt-2" name="description" id="message" cols="30" rows="4" placeholder="Write your body"></textarea>
                                </div>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @endif
                            <div class="row">
                                <div class="col-md-12 form-group mt-3 mb-1">
                                    <input type="submit" value="Create" class="btn btn-info rounded-0 py-2 px-4">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>