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
                    <div class="text-content text-dark mb-1">
                        <form action="{{ route('users.profile.update', ['uuid' => $user->uuid]) }}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                    <span class="text bg-light p-1">First Name</span>
                                    <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-input text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                    <span class="text bg-light p-1">Last Name</span>
                                    <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-input text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                    <span class="text bg-light p-1">Email</span>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-input mt-2 text-muted" />
                                </div>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-danger mt-4">
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

                            <div class="form-field mt-4">
                                <button class="btn btn-info" type="submit">update</button>
                            </div>
                        </form>


                        <hr>
                        @if (auth()->user()?->isAdmin())
                        <a href="{{ route('users.profile.show', ['uuid' => $user->uuid]) }}" style="font-size: 14px;" class="text-dark"> show</a>
                        /
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