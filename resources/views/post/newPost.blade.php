<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create new post</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('posts.store') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-6 col-lg-3 mt-2">
                                    <div class="form-group">
                                        <select class="form-input" id="exampleFormControlSelect1" name="category_id">
                                            @foreach (App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}"> {{ $category->title }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-6 col-lg-9 mt-2">
                                    <input type="text" class="form-input" name="title" id="name" placeholder="title">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 mt-3">
                                    <textarea class="form-text-area" name="body" id="message" cols="30" rows="4" placeholder="Write your body"></textarea>
                                </div>
                            </div>

                            <div class="container">
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6" id="one">
                                        <h5 class="mt-4">Upload image</h5>
                                        <div id="uploader form-input">
                                            <div class="row uploadDoc mt-4 ">
                                                <div class="col-sm-3 ">
                                                    <div class="fileUpload btn btn-orange ">
                                                        <input name="image" type="file" class="upload up " />
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
                                    <input type="submit" value="Create" class="btn btn-info shadow py-2  px-4 rounded">
                                </div>
                            </div>
                        </form>

                        <hr>
                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">Back to Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>