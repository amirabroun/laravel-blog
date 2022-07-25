<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title>users</title>

    <style>
        body {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            margin-top: 20px;
        }

        .btn-circle.btn-lg,
        .btn-group-lg>.btn-circle.btn {
            width: 50px;
            height: 50px;
            padding: 14px 15px;
            font-size: 18px;
            line-height: 23px;
        }

        .text-muted {
            color: #8898aa !important;
        }

        [type=button]:not(:disabled),
        [type=reset]:not(:disabled),
        [type=submit]:not(:disabled),
        button:not(:disabled) {
            cursor: pointer;
        }

        .user-table tbody tr .category-select {
            max-width: 15px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Manage Users</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap user-table mb-1">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-0 font-medium"  style="font-size: 14px;">Name</th>
                                    <th scope="col" class="border-0 font-medium"  style="font-size: 14px;">Email</th>
                                    <th scope="col" class="border-0 font-medium"  style="font-size: 14px;">Student Number</th>
                                    <th scope="col" class="border-0 font-medium"  style="font-size: 14px;">Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                <tr>
                                    <td>
                                        <h6 class="font-medium mb-0" style="font-size: 14px;"> {{ $user->first_name . ' ' . $user->last_name }} </h6>
                                    </td>
                                    <td>
                                        <span class="font-medium text-muted" style="font-size: 14px;">{{ $user->email }}</span><br>
                                    </td>
                                    <td>
                                        <span class="font-medium text-muted" style="font-size: 14px;">{{ $user->student_number }}</span><br>
                                    </td>
                                    <td>
                                        <span class="font-medium text-muted" style="font-size: 14px;">{{ $user->created_at }}</span><br>
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
</body>

</html>