<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url('/css/bootstrap.css') }}">
</head>

<body>
    <div class="card card-body text-dark">
        <table class="table no-wrap user-table mb-1">
            <thead>
                <tr>
                    <th>نام و نام خانوادگی</th>
                    <th>نام کاربری منحصر به فرد</th>
                    <th>تاریخ ثبت نام</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $uuid => $user)
                <tr>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['username'] }}</td>
                    <td>{{ $user['created_at'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>