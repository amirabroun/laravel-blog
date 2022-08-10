<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Category</title>

    @include('partials.abstract-css')
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