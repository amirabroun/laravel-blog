<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">

    <title>Create new post</title>
    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #fff;
            line-height: 1.9;
            color: #8c8c8c;
            position: relative;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-family: "Roboto", sans-serif;
            color: #000;
        }

        a {
            -webkit-transition: .3s all ease;
            -o-transition: .3s all ease;
            transition: .3s all ease;
        }

        a,
        a:hover {
            text-decoration: none !important;
        }

        .text-black {
            color: #000;
        }

        .content {
            padding: 7rem 0;
        }

        .heading {
            font-size: 2.5rem;
            font-weight: 900;
        }

        .form-control {
            border: none;
            border-bottom: 1px solid #ccc;
            padding-left: 0;
            padding-right: 0;
            border-radius: 0;
            background: none;
        }

        .form-control:active,
        .form-control:focus {
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            border-color: #000;
        }

        .col-form-label {
            color: #000;
            font-size: 13px;
        }

        .btn,
        .form-control,
        .custom-select {
            height: 45px;
            border-radius: 0;
        }

        .custom-select {
            border: none;
            border-bottom: 1px solid #ccc;
            padding-left: 0;
            padding-right: 0;
            border-radius: 0;
        }

        .custom-select:active,
        .custom-select:focus {
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            border-color: #000;
        }

        .btn {
            border: none;
            border-radius: 0;
            font-size: 11px;
            letter-spacing: .2rem;
            text-transform: uppercase;
            border-radius: 30px !important;
        }

        .btn.btn-primary {
            border-radius: 30px;
            background: #ef4339;
            color: #fff;
            -webkit-box-shadow: 0 15px 30px 0 rgba(239, 67, 57, 0.2);
            box-shadow: 0 15px 30px 0 rgba(239, 67, 57, 0.2);
        }

        .btn:hover {
            color: #fff;
        }

        .btn:active,
        .btn:focus {
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        .contact-wrap {
            -webkit-box-shadow: 0 0px 20px 0 rgba(0, 0, 0, 0.05);
            box-shadow: 0 0px 20px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #efefef;
        }

        .contact-wrap .col-form-label {
            font-size: 14px;
            color: #b3b3b3;
            margin: 0 0 10px 0;
            display: inline-block;
            padding: 0;
        }

        .contact-wrap .form,
        .contact-wrap .contact-info {
            padding: 40px;
        }

        .contact-wrap .contact-info {
            color: rgba(255, 255, 255, 0.5);
        }

        .contact-wrap .contact-info ul li {
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.5);
        }

        .contact-wrap .contact-info ul li .wrap-icon {
            font-size: 20px;
            color: #fff;
            margin-top: 5px;
        }

        .contact-wrap .form {
            background: #fff;
        }

        .contact-wrap .form h3 {
            color: #000;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .contact-wrap .contact-info {
            height: 100vh;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }

        .contact-wrap .contact-info a {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        @media (max-width: 1199.98px) {
            .contact-wrap .contact-info {
                height: 400px !important;
            }
        }

        .contact-wrap .contact-info h3 {
            color: #fff;
            font-size: 20px;
            margin-bottom: 30px;
        }

        label.error {
            font-size: 12px;
            color: red;
        }

        #message {
            resize: vertical;
        }

        #form-message-warning,
        #form-message-success {
            display: none;
        }

        #form-message-warning {
            color: #B90B0B;
        }

        #form-message-success {
            color: #55A44E;
            font-size: 18px;
            font-weight: bold;
        }

        .submitting {
            float: left;
            width: 100%;
            padding: 10px 0;
            display: none;
            font-weight: bold;
            font-size: 12px;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-12">
                    <div class="form h-100">
                        <a href="/" class="link text-danger mb-4">
                            - back to blog </a>
                        <h3 class="mt-4">New Post</h3>
                        <form class="mb-1" method="post" action="{{ route('post.new') }}" id="contactForm" name="contactForm"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 form-group mb-5">
                                    <label for="title" class="col-form-label">- title :</label>
                                    <input type="text" class="form-control" name="title" id="name" placeholder="title">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-3 mt-3">
                                    <label for="message" class="col-form-label">- message :</label>
                                    <textarea class="form-control" name="message" id="message" cols="30" rows="4" placeholder="Write your message"></textarea>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row it">
                                    <div class="col-sm-offset-1 col-sm-10" id="one">
                                        <h5 class="mt-4">Upload image</h5>
                                        <div id="uploader ">
                                            <div class="row uploadDoc mt-4">
                                                <div class="col-sm-3">
                                                    <div class="fileUpload btn btn-orange">
                                                        <input name="image" type="file" class="upload up"  />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 form-group mt-5 mb-1">
                                    <input type="submit" value="Create" class="btn btn-info rounded-0 py-2 px-4">
                                    <span class="submitting"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>