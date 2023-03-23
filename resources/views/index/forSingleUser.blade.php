<div class="col mt-3 mb-5">
    <div class="row main-section">
        <div class="col-lg-1"></div>
        @isset($posts)
        <div class="col-sm-12 col-md-12 col-lg-7 mb-4  {{ !count($posts) ?: 'bg-light' }}">
            @foreach ($posts as $key => $post)
            <a class="text-dark" style="text-decoration: none;" href="{{ route('posts.show', ['uuid' => $post->uuid]) }}">
                <div class="card-body">
                    @if($post->image)
                    <img class="card-img-top w-100 mb-4" src="{{ $post->media?->first()->getUrl() }}" alt="#">
                    <br>
                    <br>
                    @endif

                    <h2 class="card-title">
                        {{ $post->title }}
                    </h2>
                    <p class="card-text">
                        {{ $post->body }}
                    </p>
                </div>
            </a>

            <div class="card-footer bg-light text-muted" style="font-size: 13.5px; border: 0 solid rgba(0, 0, 0, 0)">
                @isset($post->created_at)
                <span class="text-muted mr-2" style="font-size: 13px;">{{ date('D F j, Y, G:i', strtotime($post->created_at)) }} </span>
                @endisset

                @if (auth()->user()?->ownerOrAdmin($post))
                <button class="toggler action-post-btn" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#SupportedContent-post-{{ $post->id }}" aria-controls="SupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="toggler text-dark">act</span>
                </button>
                <div class="collapse" id="SupportedContent-post-{{ $post->id }}">
                    <ul class="navbar-nav bg-light">
                        <li class="nav-item">
                            <h1>
                                <hr>
                            </h1>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('posts.edit', ['uuid' => $post->uuid]) }}" class="mr-3 text-info">
                                edit
                            </a>
                        </li>

                        <li class="nav-item">
                            <h1></h1>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('posts.destroy', ['uuid' => $post->uuid]) }}" method="POST" id="delete-post-form-{{ $post->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <a href="javascript:void(0)" class="mr-3 text-danger" onclick="$('#delete-post-form-{{ $post->id }}').submit()">
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
                @endif
            </div>

            @if(count($posts) != $key + 1)
            <hr>
            @endif

            @endforeach
        </div>
        @endisset
        <div class="col-sm-12 col-md-12 col-lg-3 d-none d-lg-block d-xl-block">
            <div class="mb-4 bg-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item {{ !session('activeCategory') ? 'bg-light' : '' }}">
                        <a style="font-size: 17.8px;" href="{{ route('posts.index') }}" class="text-dark">
                            All
                        </a>
                    </li>

                    @php $categories = \App\Models\Category::all() @endphp

                    @if(count($categories))
                    @foreach ($categories as $category)
                    <li class="list-group-item {{ session('activeCategory') == $category->id ? 'bg-light' : '' }}">
                        <a class="text-dark" href="{{ route('categories.posts', ['uuid' => $category->uuid]) }}">
                            {{ $category->title }}
                        </a>

                        @if (auth()->user()?->isAdmin())
                        <button class="toggler action-post-btn ml-2 p-1 btn-sm btn-light text-success" style="font-size: 12px;" type="button" data-toggle="collapse" data-target="#SupportedContent-category-{{ $category->id }}" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler">act</span>
                        </button>
                        <div class="collapse" id="SupportedContent-category-{{ $category->id }}">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <h1>
                                        <hr>
                                    </h1>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('categories.edit', ['uuid' => $category->uuid]) }}" class="mr-3 text-info">
                                        edit
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <h1></h1>
                                </li>

                                <li class="nav-item">
                                    <form action="{{ route('categories.destroy', ['uuid' => $category->uuid]) }}" method="POST" id="delete-category-form-{{ $category->id }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <a href="javascript:void(0)" class="mr-3 text-danger" onclick="$('#delete-category-form-{{ $category->id }}').submit()">
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif

                    </li>
                    @endforeach
                    @endif
                </ul>

                @if (auth()->user()?->isAdmin())
                <div class="card-footer">
                    <span>
                        <a class="text-info" href="{{ route('categories.create') }}">
                            Add category
                        </a>
                    </span>
                </div>
                @endif

            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>
</div>