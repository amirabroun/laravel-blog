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
                        <div style="text-align: left;">
                            <div class="row">
                                @if($user->avatar)
                                <img src="{{ $user->avatar }}" style=" border-radius: 50px; margin-left: 25px;" class="w-25" alt="#">
                                @endif

                                <span class="mt-4 ml-4" style="font-size: large;">
                                    {{ $user->full_name }}
                                    @isset($user->resume->experiences)
                                    <span>
                                        |
                                    </span>
                                    <span class="text-dark">
                                        {{ $user->resume->experiences{0}->position }}
                                        at
                                        {{ $user->resume->experiences{0}->company }}
                                    </span>
                                    @endisset

                                    <br>
                                    <span class="text-muted">
                                        {{ $user->email }}
                                    </span>
                                </span>
                            </div>

                            <br>
                            <span>
                                {{ $user?->resume?->summary }}
                            </span>

                            @if (count($user?->resume?->skills ?? []))
                            <hr>
                            <div class="row" style="text-align: center;">
                                @foreach ($user->resume->skills as $skill)
                                <div class="skill-box col-4">
                                    <span class="title">{{ $skill->title }}</span>

                                    <div class="skill-bar">
                                        @php
                                        $percent = isset($skill->percent) ? $skill->percent : 0;
                                        @endphp
                                        <span class="skill-per" style="width: <? echo $percent . "%" ?>">
                                            <span class="tooltip"></span>
                                        </span>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                            @endif

                        </div>
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