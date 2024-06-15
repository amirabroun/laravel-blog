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
                    <th>title</th>
                    <th>body</th>
                    <th>Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $uuid => $post)
                <tr>
                    <td>{{ $post['title'] }}</td>
                    <td>{{ $post['body'] }}</td>
                    <td>{{ $post['created_at'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>