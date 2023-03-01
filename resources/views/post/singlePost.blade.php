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
                            <a class="text-muted" href="{{ route('categories.posts', ['uuid' => $post->category->uuid, 'title' => $post->category->title]) }}">
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
                            @php
                                $profileParamRoute = config('blog.can_users_register') ? ['uuid' => $post->user->id] : ['uuid' => Str::slug($post->user->full_name)]
                            @endphp
                            <a class="text-dark" href="{{ route('users.profile.show', $profileParamRoute) }}">
                                {{ $post->user->first_name . ' ' . $post->user->last_name }}
                            </a> /
                            <span style="font-size: 13px;">
                                {{ date('F j, Y, g:i a', strtotime($post->created_at)) }}
                            </span>
                        </div>

                        @if($post->labels->count())
                        <div class="tags-share">
                            <br>
                            <div class="row">
                                <div class="col">
                                    <span class="text-muted" style="font-size: 13px;">
                                        Labels :
                                    </span>
                                    @foreach ($post->labels as $label)
                                    <a class="bg-dark text-light pr-2 pl-2 rounded mr-2 mt-3" style="font-size: 14px;" href="#"> {{ $label->title }} </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr>

                        @if (auth()->user())
                        @if (auth()->user()->id == $post->user->id || auth()->user()->isAdmin())
                        <a href="{{ route('posts.edit', ['uuid' => $post->uuid, 'title' => $post->title]) }}" class="text-dark" style="font-size: 14px;"> edit</a>
                        /
                        @endif
                        @endif

                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">Back to Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>