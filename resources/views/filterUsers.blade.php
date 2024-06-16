<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url('/css/bootstrap.css') }}">
</head>

<body style="background: linear-gradient(312deg, rgba(61,55,163,1) 0%, rgba(39,39,217,1) 45%, rgba(0,212,255,1) 100%);">
    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            @isset($users)
            <div class="col-12 mt-6 card shadow-sm  card-body text-dark">
                <div class="table-responsive">
                    <table class="table no-wrap user-table mb-1">
                        <thead>
                            <tr>
                                <th class="border-0 col-4" style="font-size: 13px;">#</th>
                                <th class="border-0 col-4" style="font-size: 13px;">نام و نام خانوادگی</th>
                                <th class="border-0 col-2" style="font-size: 13px;">نام کاربری منحصر به فرد</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $countUser = 1 ?>
                            @foreach ($users as $user)
                            <tr>
                                <td class="col-2">{{ $countUser }}</td>
                                <td class="col-4 mt-4">{{ $user->full_name }}</td>
                                <td class="col-2">{{ $user->phone }}</td>
                            </tr>
                            <?php $countUser += 1; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endisset

            <div class="row mt-3">
                <div class="col-12 mb-6  text-dark">
                    <div class="text-content text-dark card card-body" style="direction: rtl;">
                        <form class="mb-1" method="post" action="{{ route('users.filter') }}" style="direction: rtl;" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <label for="count">گرفتن</label>
                                <input value="{{ $count ?? null }}" type="number" class="form-input" name="count">
                                <label for="count"> شماره تلفن رندوم از دیتابیس</label>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group mt-5 mb-1">
                                    <input class="btn btn-success shadow py-2 px-4 rounded" type="submit" value="گرفتن شماره تلفن ها " style="border-radius: 5px;" class="btn btn-success shadow py-2  px-4 rounded">
                                </div>
                            </div>
                        </form>

                        @isset($users)
                        <div style="display: flex;">
                            <form method="post" action="{{ route('users.export') }}" class="mt-1">
                                @csrf
                                <input class="btn btn-primary shadow py-2 px-4 rounded" type="submit" value="excel گرفتن خروجی">
                                <div hidden>
                                    <input name="type" value="excel">
                                    <input value="{{ $count ?? null }}" type="number" class="form-input" name="count">
                                </div>
                            </form>

                            <form method="post" action="{{ route('users.export') }}" class="mt-1">
                                @csrf
                                <input class="btn btn-primary shadow py-2 px-4 rounded" type="submit" value="pdf گرفتن خروجی">
                                <div hidden>
                                    <input name="type" value="pdf">
                                    <input value="{{ $count ?? null }}" type="number" class="form-input" name="count">
                                </div>
                            </form>

                            <form method="post" action="{{ route('users.export') }}" class="mt-1">
                                @csrf
                                <input class="btn btn-primary shadow py-2 px-4 rounded" type="submit" value="word گرفتن خروجی">
                                <div hidden>
                                    <input name="type" value="word">
                                    <input value="{{ $count ?? null }}" type="number" class="form-input" name="count">
                                </div>
                            </form>
                        </div>
                        @endisset
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>