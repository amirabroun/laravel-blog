<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ URL::asset('/resources/css/bootstrap.css') }}" rel="stylesheet" id="bootstrap-css">
    <title>Users</title>

    @include('partials.abstract-css')
    <style>
        .table td,
        .table th {
            padding-right: 3px;
            padding-left: 3px;
        }
    </style>
</head>

<body>
    @include('partials.nav')

    <div class="row">
        <div class="col mt-4" style="margin-bottom: 100px;">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-sm-12 col-lg-8 p-4">
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
                <div class="col-2"></div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>