<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Soft Login</title>

    @include('partials.abstract-css')
    <style>
        body {
            color: #4A4A4A;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background-color: #E4E5E6;
            background-image: linear-gradient(0deg, #FFDEE9 0%, #B5FFFC 100%);
        }

        form {
            width: 350px;
            position: relative;
        }

        form .form-field::before {
            font-size: 20px;
            position: absolute;
            left: 15px;
            top: 17px;
            color: #888888;
            content: " ";
            display: block;
            background-size: cover;
            background-repeat: no-repeat;
        }

        form .form-field:nth-child(1)::before {
            width: 20px;
            height: 20px;
            top: 15px;
        }

        form .form-field:nth-child(2)::before {
            width: 16px;
            height: 16px;
        }

        form .form-field {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
        }

        form input {
            font-family: inherit;
            width: 100%;
            outline: none;
            background-color: #fff;
            border-radius: 4px;
            border: none;
            display: block;
            padding: 0.9rem 0.7rem;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
            font-size: 17px;
            color: #4A4A4A;
            text-indent: 40px;
        }

        form .btn {
            outline: none;
            border: none;
            cursor: pointer;
            display: inline-block;
            margin: 0 auto;
            padding: 0.9rem 2.5rem;
            text-align: center;
            background-color: #47AB11;
            color: #fff;
            border-radius: 4px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
            font-size: 17px;
        }
    </style>
</head>

<body>
    <form action="{{ route('softLogin') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-field">
            <input name="code" type="text" placeholder="Soft Code" required />
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
        <div class="form-field mt-1">
            <button class="btn" type="submit">Soft log in</button>
        </div>
        <div class="form-field">
            <h6 style="margin: 0 auto; ">
                or <a class="text-dark" href=" {{ route('login.index') }} "> Log in?</a>
            </h6>
        </div>
    </form>
</body>

</html>