@extends('layouts.app')

<div class="container my-5">
    <div class="card">
        <div class="card-header">
            <h2>create article</h2>
        </div>
        <div class="card-body">
            <p class="card-text">create youre article</p>
            <form action="{{ route('create') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="Title">Title</label>
                            <input class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label for="author">author</label>
                            <input class="form-control" name="author">
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="body">body</label>
                                <textarea class="form-control" name="body"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-success mt-4 w-50">create</button>
                </div>
            </form>
        </div>
    </div>
</div>