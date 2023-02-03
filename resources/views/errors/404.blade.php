<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>

    @include('partials.abstract-css')
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="form-field">
        <h5 style="margin: 0 auto;">
            <span class="mr-2 text-muted">
                404 Not Found
            </span>

            |
            <a class="text-dark ml-2" href=" {{ route('posts.index') }} ">
                Back to blog
            </a>
        </h5>
    </div>
</body>

</html>