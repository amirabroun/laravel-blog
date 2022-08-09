<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title>Users</title>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #FFDEE9;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        .card {
            color: #4A4A4A;
            display: flex;
            min-height: 100vh;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .footer-section {
            position: fixed;
            background: #E8CBC0;
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
            padding-top: 25px;
            padding-bottom: 50px;
            margin-top: 4000px;
        }
    </style>
</head>

<body>
    @include('partials.nav')

    <div class="container-fluid mt-2 mb-5">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Manage Users</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap user-table mb-1">
                            <thead>
                                <tr>
                                    <th class="border-0 col-4" style="font-size: 13px;">Name</th>
                                    <th class="border-0 col-4" style="font-size: 12px;">Student Number</th>
                                    <th class="border-0 col-4" style="font-size: 13px;">Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                <tr>
                                    <td>
                                        <span class="" style="font-size: 14px;">
                                            <a href="{{ route('users.profile.show', ['id' => $user->id]) }}" class="text-info">
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </a>
                                            <h6 class="mb-0 text-muted mt-2" style="font-size: 14px;"> {{ $user->email }} </h6>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: 14px;">{{ $user->student_number }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: 14px;">{{ date('Y / M / D', strtotime($user->created_at)) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    @include('partials.footer')
</body>

</html>