<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    @if (config('blog.can_users_register'))

    @include('index.forMultiUser')

    @else

    @include('index.forSingleUser')

    @endif

    @include('partials.footer')
</body>

</html>