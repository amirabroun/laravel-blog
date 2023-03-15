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
                <div class="card shadow single-blog-post card-body text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('posts.update', ['uuid' => $post->uuid]) }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-6 col-lg-3 mt-2">
                                    <div class="form-group">
                                        <select class="form-input" id="exampleFormControlSelect1" name="category_id">
                                            <option value="{{ $post->category?->id }}"> {{ $post->category?->title }} </option>
                                            @foreach (App\Models\Category::all()->except($post->category?->id) as $category)
                                            <option value="{{ $category->id }}"> {{ $category->title }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-9 mt-2">
                                    <input type="text" class="form-input" name="title" id="name" placeholder="Title" value="{{ $post->title }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3 mt-3">
                                    <textarea class="form-text-area" name="body" id="message" cols="30" rows="6">{{ $post->body }}</textarea>
                                </div>
                            </div>

                            <hr>
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div id="uploader">
                                            <div class="row uploadDoc mt-4">
                                                @if(File::exists(base_path('/public/image/') . $post->image_url) && isset($post->image_url))
                                                <br>
                                                <hr>
                                                <img class="card-img-top w-100 mb-4" src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="#">
                                                <br>
                                                @endif

                                                <div>
                                                    <input name="image" type="file" class="upload up form-input  action-post-btn text-dark" />
                                                </div>

                                                <div class="mt-5 d-block d-lg-none">
                                                    <h1></h1>
                                                </div>

                                                @if(File::exists(base_path('/public/image/') . $post->image_url) && isset($post->image_url))
                                                <div class="mt-1 ml-2">
                                                    <a class="action-post-btn text-danger bg-light" style="font-size: 12px;" type="submit" onclick="$('#delete-post-file-form-{{ $post->id }}').submit()">
                                                        Delete File
                                                    </a>
                                                </div>
                                                @endif
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
                            @isset($updateMessage)
                            <div class="alert alert-info mt-4">
                                <ul>
                                    <li>{{ $updateMessage }}</li>
                                </ul>
                            </div>
                            @endisset

                            <div class="row">
                                <div class="col-md-12 form-group mt-5 mb-1">
                                    <input type="submit" value="Update" style="border-radius: 5px;" class="btn btn-info shadow py-2  px-4 rounded">
                                </div>
                            </div>
                        </form>

                        <form action="{{ route('posts.files.delete', ['uuid' => $post->uuid]) }}" method="POST" id="delete-post-file-form-{{ $post->id }}">
                            @csrf @method('DELETE')
                        </form>

                        <hr>
                        <a href="{{ route('posts.show', ['uuid' => $post->uuid, 'title' => $post->title]) }}" class="text-dark" style="font-size: 14px;"> show</a>
                        /
                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">Back to Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>