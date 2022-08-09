<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">

    <title>Create new post</title>
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
                    <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>
                    <hr>
                    <br>
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('posts.store') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label for="title" class="col-form-label">title :</label>
                                    <input type="text" class="form-control" name="title" id="name" placeholder="title">
                                </div>

                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="col-form-label">Category :</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="category_id">
                                            @foreach (App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}"> {{ $category->title }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 mt-3">
                                    <label for="body" class="col-form-label">body :</label>
                                    <textarea class="form-control" name="body" id="message" cols="30" rows="4" placeholder="Write your body"></textarea>
                                </div>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6" id="one">
                                        <h5 class="mt-4">Upload image</h5>
                                        <div id="uploader ">
                                            <div class="row uploadDoc mt-4">
                                                <div class="col-sm-3">
                                                    <div class="fileUpload btn btn-orange">
                                                        <input name="image" type="file" class="upload up" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                <div class="col-md-12 form-group mt-5 mb-1">
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