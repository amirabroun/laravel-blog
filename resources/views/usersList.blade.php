<html lang="en" style="text-align: right;">

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
        <div class="col" style="margin-bottom: 100px;">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-sm-12 col-lg-8 p-4">
                    <div class="card shadow-sm single-blog-post card-body text-dark">
                        <div class="table-responsive">
                            <table class="table no-wrap user-table mb-1">
                                <thead>
                                    <tr>
                                        <th class="border-0 col-4" style="font-size: 13px;">اسم</th>
                                        <th class="border-0 col-2" style="font-size: 13px;"></th>
                                        <th class="border-0 col-6" style="font-size: 13px;">اضافه شده در</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="col-4 mt-4">
                                            <span class="" style="font-size: 14px;">
                                                <a href="{{ route('users.profile.show', ['id' => $user->id]) }}" class="text-info">
                                                    {{ $user->first_name . ' ' . $user->last_name }}
                                                </a>
                                                <h6 class="mb-0 text-muted mt-2" style="font-size: 12px;"> {{ $user->email }} </h6>
                                            </span>
                                        </td>
                                        <td class="col-1"></td>
                                        <td class="col-5">
                                            <span class="text-muted" style="font-size: 12px;">{{ $user->created_at }}</span>
                                        </td>
                                        <td class="col-1">
                                            @if (auth()->user()?->isAdmin())
                                            <button class="toggler action-post-btn ml-2 p-1 btn-sm btn-light text-success" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#SupportedContent-user-{{ $user->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                                <span class="toggler">. . .</span>
                                            </button>
                                            <div class="collapse" id="SupportedContent-user-{{ $user->id }}">
                                                <ul class="navbar-nav">
                                                    <li class="nav-item">
                                                        <h1>
                                                            <hr>
                                                        </h1>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="{{ route('users.profile.edit', ['id' => $user->id]) }}" class=" text-info">
                                                            ویرایش
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <h1></h1>
                                                    </li>

                                                    <li class="nav-item">
                                                        <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" id="delete-form-user-{{ $user->id }}">
                                                            @csrf @method('DELETE')
                                                        </form>
                                                        <a href="javascript:void(0)" class="text-danger" onclick="$('#delete-form-user-{{ $user->id }}').submit()">
                                                            حذف
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
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