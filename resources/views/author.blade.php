@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="margin-top: 10px;">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('author') }}">
                        <div class="row mb-3">
                            <label for="phone" class="col-4 col-form-label text-md-end">Phone number:</label>
                            <div class="col-6">
                                <input type="text" class="form-control" name="phone">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary">
                                    submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div style="margin-top: 10px;">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>name</th>
                                <th>email</th>
                            </tr>
                        </thead>
                        @foreach ($authors as $key => $author)
                        <tbody>
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ url('author/' . $author->name) }}">{{ $author->name  }}</a></td>
                                <td>{{ $author->eamil ?? 'null' }}</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>