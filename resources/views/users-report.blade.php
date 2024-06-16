<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url('/css/bootstrap.css') }}">
</head>

<body>
    <div class="card card-body text-dark">
        <span style="direction: rtl;">
            گزارش خروجی از دفترچه تلفن
        </span>
        <br><br>
        <hr>
        @foreach ($users as $uuid => $user)
        <span>{{ $user['name'] }} نام</span>
        <span>{{ $user['phone'] }} شماره تلفن</span>

        <br>
        <hr>
        <br>

        @endforeach
    </div>
</body>

</html>