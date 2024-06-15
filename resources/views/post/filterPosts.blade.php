<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Post</title>

    @include('partials.abstract-css')
</head>

<body>
    @include('partials.nav')

    <div class="container mt-4" style="margin-bottom: 110px;">
        <div class="col-md-10">
            <div class="row">
                <div class="col-12 mb-6 card shadow single-blog-post card-body text-dark">
                    <div class="text-content text-dark">
                        <form class="mb-1" method="post" action="{{ route('posts.filter') }}" id="contactForm" name="contactForm" enctype="multipart/form-data">
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
                                    <input type="submit" value="Get" style="border-radius: 5px;" class="btn btn-info shadow py-2  px-4 rounded">
                                </div>
                            </div>
                        </form>

                        @isset($posts)
                        <form method="post" action="{{ route('posts.export') }}" class="mt-3">
                            @csrf
                            <input type="submit" value="genrate excel" class="btn btn-secondary rounded">
                            <div hidden>
                                <input name="type" value="excel">
                                <input name="from" value="{{ $from ?? null }}">
                                <input name="to" value="{{ $to ?? null }}">
                            </div>
                        </form>

                        <form method="post" action="{{ route('posts.export') }}" class="mt-1">
                            @csrf
                            <input type="submit" value="genrate pdf" class="btn btn-secondary rounded">
                            <div hidden>
                                <input name="type" value="pdf">
                                <input name="from" value="{{ $from ?? null }}">
                                <input name="to" value="{{ $to ?? null }}">
                            </div>
                        </form>

                        <form method="post" action="{{ route('posts.export') }}" class="mt-1">
                            @csrf
                            <input type="submit" value="genrate word" class="btn btn-secondary rounded">
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
            @isset($posts)
            <div class="col-12 mt-6 card shadow-sm single-blog-post card-body text-dark">
                <div class="table-responsive">
                    <table class="table no-wrap user-table mb-1">
                        <thead>
                            <tr>
                                <th class="border-0 col-4" style="font-size: 13px;">Name</th>
                                <th class="border-0 col-2" style="font-size: 13px;"></th>
                                <th class="border-0 col-6" style="font-size: 13px;">Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td class="col-4 mt-4">
                                    <a href="{{ route('posts.show', ['uuid' => $post->uuid]) }}">
                                        <h6 class="mb-0 text-muted mt-2"> {{ $post->title }} </h6>
                                    </a>
                                </td>
                                <td class="col-2"></td>
                                <td class="col-6">
                                    <span class="text-muted">{{ $post->created_at }}</span>
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

    @include('partials.footer')
</body>

</html>