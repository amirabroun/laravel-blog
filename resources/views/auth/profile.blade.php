<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title> {{ $user->first_name . ' ' . $user->last_name }} </title>

    <style>
        body {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
        }

        .emp-profile {
            padding: 4%;
            margin-top: 12%;
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
            margin-top: -20%;
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
            margin-top: 5%;
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
            margin-top: -15%;
        }

        .profile-work p {
            font-size: 12px;
            color: #818182;
            font-weight: 600;
            margin-top: 10%;
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
        <div class="row">
            <div class="col-md-12">
                <div class="profile-head">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="/">
                                About
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if ($updatePermission)
        <div class="row mt-2">
            <div class="col-md-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active">
                        <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label>First Name :</label>
                                </div>
                                <div class="form-outline">
                                    <input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label>Last Name : </label>
                                </div>
                                <div class="form-outline">
                                    <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label>ID : </label>
                                </div>
                                <div class="form-outline">
                                    <input type="text" name="student_number" value="{{ $user->student_number }}" class="form-control" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label>Email : </label>
                                </div>
                                <div class="form-outline">
                                    <input type="text" name="email" value="{{ $user->email }}" class="form-control" />
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
                    </div>
                </div>
            </div>
        </div>
        @else

        <div class="row mt-2">
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <label>First Name : </label>
                            </div>
                            <div class="col-md-6">
                                <p> {{ $user->first_name }} </p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Last Name : </label>
                            </div>
                            <div class="col-md-6">
                                <p> {{ $user->last_name }} </p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>ID : </label>
                            </div>
                            <div class="col-md-3">
                                <p> {{ $user->student_number }} </p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Email : </label>
                            </div>
                            <div class="col-md-6">
                                <p> {{ $user->email }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>

</html>