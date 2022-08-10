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

    @if ($updatePermission)
    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="card shadow-sm card-body text-dark">
                    <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>
                    <hr>
                    <br>
                    <div class="text-content text-dark">
                        <form action="{{ route('users.profile.update', ['id' => $user->id]) }}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf

                            <div class=" row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">First Name</span>
                                    <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Last Name</span>
                                    <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">ID</span>
                                    <input type="text" name="student_number" value="{{ $user->student_number }}" class="form-control text-muted mt-2" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Email</span>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control mt-2 text-muted" />
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 mt-4">
                                    <span class="text">Soft Code</span>
                                    <input type="text" name="soft_code" value="{{ $user->soft_login_code }}" class="form-control mt-2 text-muted" />
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

                            <div class="form-field mt-5">
                                <button class="btn btn-info" type="submit">update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-sm-12 col-md-10">
            <div class="row">
                <div class="card shadow-sm card-body text-dark">
                    <a class="text-danger" href="{{ route('posts.index') }}">Back to Blog</a>
                    <hr>
                    <br>

                    <div class="row mt-4">
                        <div class="col-4 col-md-2">
                            <label>First Name : </label>
                        </div>
                        <div class="col-6 col-md-6">
                            <p> {{ $user->first_name }} </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-4 col-md-2">
                            <label>Last Name : </label>
                        </div>
                        <div class="col-6 col-md-6">
                            <p> {{ $user->last_name }} </p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-2 col-md-1">
                            <label>ID : </label>
                        </div>
                        <div class="col-6 col-md-10">
                            <p> {{ $user->student_number }} </p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-3 col-md-2">
                            <label>Email : </label>
                        </div>
                        <div class="col-9 col-md-10">
                            <p> {{ $user->email }} </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    @include('partials.footer')
</body>

</html>