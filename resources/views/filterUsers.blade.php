<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url('/css/bootstrap.css') }}">
</head>

<body>

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="col-12 mb-6  text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('users.filter') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <label for="from">from</label>
                                    <input value="{{ $from ?? null }}" type="datetime-local" class="form-input" name="from">
                                </div>
                                <div class="col-6">
                                    <label for="to">to</label>
                                    <input value="{{ $to ?? null }}" type="datetime-local" class="form-input" name="to">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group mt-5 mb-1">
                                    <input type="submit" value="فیلتر کاربران" style="border-radius: 5px;" class="btn btn-success shadow py-2  px-4 rounded">
                                </div>
                            </div>
                        </form>

                        @isset($users)
                        <form method="post" action="{{ route('users.export') }}" class="mt-3">
                            @csrf
                            <input type="submit" value="excel گرفتن خروجی">
                            <div hidden>
                                <input name="type" value="excel">
                                <input name="from" value="{{ $from ?? null }}">
                                <input name="to" value="{{ $to ?? null }}">
                            </div>
                        </form>

                        <form method="post" action="{{ route('users.export') }}" class="mt-1">
                            @csrf
                            <input type="submit" value="pdf گرفتن خروجی">
                            <div hidden>
                                <input name="type" value="pdf">
                                <input name="from" value="{{ $from ?? null }}">
                                <input name="to" value="{{ $to ?? null }}">
                            </div>
                        </form>

                        <form method="post" action="{{ route('users.export') }}" class="mt-1">
                            @csrf
                            <input type="submit" value="word گرفتن خروجی" >
                            <div hidden>
                                <input name="type" value="word">
                                <input name="from" value="{{ $from ?? null }}">
                                <input name="to" value="{{ $to ?? null }}">
                            </div>
                        </form>
                        @endisset
                    </div>
                </div>
            </div>
            @isset($users)
            <div class="col-12 mt-6 card shadow-sm  card-body text-dark">
                <div class="table-responsive">
                    <table class="table no-wrap user-table mb-1">
                        <thead>
                            <tr>
                                <th class="border-0 col-4" style="font-size: 13px;">نام و نام خانوادگی</th>
                                <th class="border-0 col-2" style="font-size: 13px;">نام کاربری منحصر به فرد</th>
                                <th class="border-0 col-6" style="font-size: 13px;">ثبت نام در تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="col-4 mt-4">
                                    {{ $user->full_name }}
                                </td>
                                <td class="col-2">{{ $user->username }}</td>
                                <td class="col-6">
                                    {{ $user->created_at }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endisset
        </div>
    </div>
    </div>

</body>

</html>