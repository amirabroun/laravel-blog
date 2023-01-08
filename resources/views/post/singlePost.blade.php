<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $post->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @include('partials.abstract-css')

    <style>
        body,
        html,
        div {
            direction: rtl;
            text-align: right;
        }
    </style>
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
                                {{ $post->user->full_name }}
                            </a> /


                            <span style="font-size: 13px;">
                                {{ $post->created_at }}
                            </span>
                            /

                            @auth
                            @if ($post->canAuthUserLikeThisPost)
                            <a href="{{ route('posts.like', ['id' => $post->id]) }}" style="text-decoration: none;" class="text-secondary">
                                <i id="like-post" class="fa fa-thumbs-up" style="font-size:19px"></i>
                            </a>
                            @else
                            <a href="{{ route('posts.like', ['id' => $post->id]) }}" style="text-decoration: none;" class="text-danger">
                                <i class="fa fa-thumbs-up" style="font-size:19px"></i>
                            </a>
                            @endif
                            @endauth

                            @guest
                            <i class="fa fa-thumbs-up text-danger" style="font-size:19px"></i>
                            @endguest

                            {{ $post->count_likes }}
                        </div>

                        @if($post->labels->count())
                        <div class="tags-share mr-0">
                            <br>
                            <div class="row">
                                <div class="col">
                                    @foreach ($post->labels as $label)
                                    <a class="bg-dasrk text-dark text-muted ml-1 rounded mt-3" style="font-size: 14px;" href="#"> {{ $label->title }} </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr>

                        @if (auth()->user()?->isAdmin())
                        <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="text-dark" style="font-size: 14px;"> ویرایش</a>
                        /
                        @endif

                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">بلاگ</a>
                    </div>
                </div>

                @auth
                <div class="card w-100 mt-1">
                    <form class="mb-1" method="post" action="{{ route('posts.comments.store', ['post_id' => $post->id]) }}" id="contactForm" name="contactForm" style="text-align: center;" enctype="multipart/form-data">
                        @csrf
                        <textarea class="form-text-area mt-1" name="body" id="message" cols="25" rows="3" placeholder="کامنت بزارید ..." required></textarea>
                        <input type="submit" value="ذخیره" class="btn bg-secondary text-light form-text-area py-2  px-4 rounded">
                    </form>
                </div>
                @endauth

                @foreach ($post->comments as $comment)
                <div class="col-12" style="padding-right: 0;">
                    <div class="card mt-2 col-{{ $comment->size }}" style="border-radius: 20px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="text-muted">
                                    <p class="card-text">
                                        {{ $comment->body }}
                                    </p>
                                    <span style="font-size: 13px;">
                                        {{ $comment->created_at }}
                                    </span>
                                    /
                                    <a class="text-dark" href="{{ route('users.profile.show', ['id' => $post->user->id]) }}" style="font-size: 13px;">
                                        {{ $comment->user->full_name }}
                                    </a>

                                    @auth
                                    @if (auth()->user()->id == $comment->user->id || auth()->user()->isAdmin())
                                    <form action="{{ route('posts.comments.destroy', ['post_id' => $post->id,'id' => $comment->id]) }}" hidden method="Post" id="delete-form-{{ $comment->id }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    /
                                    <a href="javascript:void(0)" class=" text-danger" onclick="$('#delete-form-{{ $comment->id }}').submit()">
                                        حذف
                                    </a>
                                    @endif
                                    @endauth

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>