<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $user->first_name . ' ' . $user->last_name }} </title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-sm-12 col-md-10">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
                    <div class="text-content text-dark mb-1 mt-2">
                        <span>
                            {{ $user->first_name }}
                        </span>
                        <span class="mr-3">
                            : اسم
                        </span>


                        <br class="mb-4">

                        <span>
                            {{ $user->last_name }}
                        </span>
                        <span class="mr-3">
                            : فامیل
                        </span>

                        <br class="mb-4">
                        <span>
                            {{ $user->email }}
                        </span>
                        <span class="mr-3">
                            : ایمیل
                        </span>

                        <hr>

                        @if (auth()->user()?->isAdmin() || auth()->user()?->id == $user->id)
                        <a href="{{ route('users.profile.edit', ['id' => $user->id]) }}" style="font-size: 14px;" class="text-info"> ویرایش</a>
                        /
                        @endif

                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">بلاگ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>