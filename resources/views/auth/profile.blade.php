<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title> {{ $user->first_name . ' ' . $user->last_name }} </title>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            color: #4A4A4A;
            display: flex;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background-color: #FFDEE9;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        .emp-profile {
            justify-content: center;
            padding: 4%;
            margin-bottom: 3%;
            border-radius: 1.5rem;
            background: #fff;
        }

        .profile-img {
            text-align: center;
        }

        .profile-img img {
            width: 70%;
            height: 100%;
        }

        .profile-img .file {
            position: relative;
            overflow: hidden;
            width: 70%;
            border: none;
            border-radius: 0;
            font-size: 15px;
            background: #212529b8;
        }

        .profile-img .file input {
            position: absolute;
            opacity: 0;
            right: 0;
            top: 0;
        }

        .profile-head h5 {
            color: #333;
        }

        .profile-head h6 {
            color: #0062cc;
        }

        .profile-edit-btn {
            border: none;
            border-radius: 1.5rem;
            width: 70%;
            padding: 2%;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
        }

        .proile-rating {
            font-size: 12px;
            color: #818182;
        }

        .proile-rating span {
            color: #495057;
            font-size: 15px;
            font-weight: 600;
        }

        .profile-head .nav-tabs {
            margin-bottom: 5%;
        }

        .profile-head .nav-tabs .nav-link {
            font-weight: 600;
            border: none;
        }

        .profile-head .nav-tabs .nav-link.active {
            border: none;
            border-bottom: 2px solid #0062cc;
        }

        .profile-work {
            padding: 14%;
        }

        .profile-work p {
            font-size: 12px;
            color: #818182;
            font-weight: 600;
        }

        .profile-work a {
            text-decoration: none;
            color: #495057;
            font-weight: 600;
            font-size: 14px;
        }

        .profile-work ul {
            list-style: none;
        }

        .profile-tab label {
            font-weight: 600;
        }

        .profile-tab p {
            font-weight: 600;
            color: #0062cc;
        }
    </style>
</head>

<body>
    <div class="container emp-profile">
        <a class="text-danger" href="{{ route('blog') }}">Back to Blog</a>

        @if ($updatePermission)
        <div class="row">
            <div class="col">
                <hr>
                <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf

                    <div class=" row">
                        <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                            <span class="text">First Name</span>
                            <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control mt-2" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                            <span class="text">Last Name</span>
                            <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control mt-2" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                            <span class="text">ID</span>
                            <input type="text" name="student_number" value="{{ $user->student_number }}" class="form-control mt-2" />
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                            <span class="text">Email</span>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control mt-2" />
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

        @else
        <div class="row">
            <div class="col">
                <hr>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <label>First Name : </label>
                    </div>
                    <div class="col-md-6">
                        <p> {{ $user->first_name }} </p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3">
                        <label>Last Name : </label>
                    </div>
                    <div class="col-md-6">
                        <p> {{ $user->last_name }} </p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <label>ID : </label>
                    </div>
                    <div class="col-md-3">
                        <p> {{ $user->student_number }} </p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <label>Email : </label>
                    </div>
                    <div class="col-md-6">
                        <p> {{ $user->email }} </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

</body>

</html>