<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">

    <title>Create new post</title>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            color: #4A4A4A;
            display: flex;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background-color: #FFDEE9;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        .emp-profile {
            justify-content: center;
            padding: 2%;
            margin-bottom: 1%;
            border-radius: 1rem;
            background: #ffff;
        }
    </style>
</head>

<body>
    <div class="container emp-profile">
        <div class="row">
            <div class="col">
                <a class="text-danger" href="{{ route('blog') }}">Back to Blog</a>
                <hr>
                <br>
                <h3>New Post</h3>

                <form class="mb-1" method="post" action="{{ route('posts.store') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 ">
                            <label for="title" class="col-form-label">title :</label>
                            <input type="text" class="form-control" name="title" id="name" placeholder="title">
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1"  class="col-form-label">Category :</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option>1</option>
                                    <option>2</option>
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
                            <span class="submitting"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>