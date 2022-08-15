<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $post->title }}</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    @if(File::exists(base_path('/public/image/') . $post->image_url) && isset($post->image_url))
                    <img src="{{ URL::asset('/public/image/' . $post->image_url) }}" alt="#">
                    <hr>
                    @endisset

                    <div class="text-content text-dark">
                        <p>
                            @isset($post->category)
                            <a class="text-muted" href="{{ route('categories.show', ['title' => $post->category->title]) }}">
                                {{ $post->category->title }}
                            </a>/
                            @endisset

                            <span class="ml-2" style="font-size: 23px;">
                                {{ $post->title }}
                            </span>
                        </p>

                        <div class="text-muted">
                            <p class="text-dark"> {{ $post->body }}</p>
                            <br>
                            <br>
                            <a class="text-dark" href="{{ route('users.profile.show', ['id' => $post->user->id]) }}">
                                {{ $post->user->first_name . ' ' . $post->user->last_name }}
                            </a> /
                            <span style="font-size: 13px;">
                                {{ date('F j, Y, g:i a', strtotime($post->created_at)) }}
                            </span>
                        </div>

                        <hr>
                        <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="text-info"> edit</a>
                        /
                        <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>

                        @if($post->labels->count())
                        <div class="tags-share">
                            <div class="row">
                                <div class="col">
                                    labels :
                                    @foreach ($post->labels as $label)
                                    <a class="bg-light text-dark  rounded mr-1 mt-3" href="#"> {{ $label->title }} </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>