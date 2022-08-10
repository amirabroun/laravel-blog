<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title>Users</title>

    <style>
        body,
        html {
            font-family: 'Kanit', sans-serif;
            height: 100%;
            min-height: 100%;
            overflow-x: hidden;
            background-color: #FFDEE9;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        .table td,
        .table th {
            padding-right: 3px;
            padding-left: 3px;
        }

        .footer-section {
            position: fixed;
            background: #E8CBC0;
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
            padding-top: 25px;
            padding-bottom: 50px;
            margin-top: 400px;
        }
    </style>
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 100px;">
        <div class="col-md-12">
            <div class="row">
                <div class="card shadow-sm single-blog-post card-body text-dark">
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
                                    <td class="col-4 mt-5">
                                        <span class="" style="font-size: 14px;">
                                            <a href="{{ route('users.profile.show', ['id' => $user->id]) }}" class="text-info">
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </a>
                                            <h6 class="mb-0 text-muted mt-2" style="font-size: 12px;"> {{ $user->email }} </h6>
                                        </span>
                                    </td>
                                    <td class="col-5">
                                        <span class="text-muted" style="font-size: 13px;">{{ $user->student_number }}</span>
                                    </td>
                                    <td class="col-2">
                                        <span class="text-muted" style="font-size: 12px;">{{ date('Y-m-d', strtotime($user->created_at)) }}</span>
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

    @include('partials.footer')
</body>

</html>