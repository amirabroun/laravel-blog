@extends('layouts.app')

<nav class="bg-white navbar-light">
    <div class="row">
        <div class="col-xs-12 col-sm-6 offset-sm-3 col-md-6 offset-md-3">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Message</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



This is the top/header part of my currently running application's master layout:

<!-- app/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Simple CMS" />
    <meta name="author" content="Sheikh Heera" />
    <link rel="shortcut icon" href={{ assets("favicon.png") }} />

    <title>LaraPress</title>

    <!-- Bootstrap core CSS -->
    <link href={{ asset("bootstrap/css/bootstrap.css") }} rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href={{ asset("bootstrap/css/sticky-footer-navbar.css") }} rel="stylesheet" />

    <!-- Optional theme -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-theme.min.css') }}">
</head>