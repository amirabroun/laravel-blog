@extends('layouts.app')

<div class="container my-2">
    <div class="row">
        @foreach ($articles as $article)
        <div class="col-4 bg-info border shadow bg-white">
            <a class="text-dark" href="{{ url('/blog/' . $article->title) }}">
                <div class="text-center">
                    <h1 class="mt-1">
                        {{ $article->title }}
                    </h1>
                    <hr>
                    <div>
                        <img src="{{ asset('image/' . $article->image_name ) }}" alt="{{ $article->image_name }}" class="w-75">
                    </div>
                    <div class="mt-1 mb-1">
                        <h3>
                            {{ Str::substr($article->body, 0, 20)  . ' ...'}}  
                        </h3>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>