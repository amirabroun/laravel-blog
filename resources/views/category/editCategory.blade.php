<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Category</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow single-blog-post card-body text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('categories.update', ['uuid' => $category->uuid]) }}" id="contactForm" name="contactForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-12 mt-3">
                                    <span class="text bg-light p-1">Category title  </span>
                                </div>
                                <div class="col-lg-6 col-md-8 col-sm-12 mt-2">
                                    <input type="text" class="form-input" name="title" id="name" placeholder="Title" value="{{ $category->title }}">
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

                        <hr>
                        <a href="{{ route('categories.posts', ['uuid' => $category->uuid]) }}" class="text-dark" style="font-size: 14px;"> show</a>
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