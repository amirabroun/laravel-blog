<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $user->full_name }} </title>

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
                            <h3>
                                {{ $user->full_name }}
                            </h3>

                            <h6 class="text-muted">
                                {{ $user->email }}
                            </h6>
                        </span>

                        @if (count($user->skills))
                        <div class="row">
                            @foreach ($user->skills as $skill)
                            <div class="skill-box col-4">
                                <span class="title">{{ $skill->title }}</span>

                                <div class="skill-bar">
                                    <span class="skill-per" style="width: <? echo "$skill?->percent%" ?>">
                                        <span class="tooltip"></span>
                                    </span>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        @endif

                        <hr>

                        @auth
                        @if (auth()->user()->profileOwnerOrAdmin($user))
                        <a href="{{ route('users.profile.edit', ['uuid' => $user->uuid]) }}" style="font-size: 14px;" class="text-dark"> edit</a>
                        /
                        @endif
                        @endauth

                        <a class="text-danger" href="{{ route('posts.index') }}" style="font-size: 14px;">Back to Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>