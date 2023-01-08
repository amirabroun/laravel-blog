<link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    body,
    html {
        direction: rtl;
        font-family: "Droid Arabic", "Droid Sans", sans-serif;
        height: 100%;
        min-height: 100%;
        overflow-x: hidden;
        background: #FFEEEE;
        background: #E4E5E6;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to left, #00416A, #E4E5E6);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to left, #00416A, #E4E5E6);
        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }

    a, p, h1, h2, h3, h4, h5, h6  {
        direction: rtl;
    }


    .footer-section {
        position: fixed;
        direction: rtl;
        background: #E8CBC0;
        background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
        padding-top: 25px;
        padding-bottom: 50px;
        margin-top: 4000px;
    }

    .action-post-btn {
        display: inline-block;
        direction: rtl;
        font-weight: 400;
        white-space: nowrap;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .shadow-post> :hover {
        direction: rtl;
        cursor: pointer;
        box-shadow: 0 1rem 1rem 1rem rgba(0, 0, 0.1, 0.10);
    }

    .form-input {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        background-clip: padding-box;
        border-radius: 0.25rem;
        font-family: inherit;
        direction: rtl;
        background-color: #fff;
        width: 100%;
        border: none;
        display: block;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
    }

    .form-text-area {
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        background-clip: padding-box;
        direction: rtl;
        border-radius: 0.25rem;
        font-family: inherit;
        background-color: #fff;
        width: 100%;
        border: none;
        display: block;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
    }

    span {
        direction: rtl;
    }
</style>