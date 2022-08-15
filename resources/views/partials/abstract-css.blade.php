<link rel="stylesheet" href="{{ URL::asset('/resources/css/bootstrap.css') }}">

<style>
    body,
    html {
        font-family: 'Kanit', sans-serif;
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

    .footer-section {
        position: fixed;
        background: #E8CBC0;
        background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
        padding-top: 25px;
        padding-bottom: 50px;
        margin-top: 4000px;
    }

    .action-post-btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
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
        cursor: pointer;
        box-shadow: 0 1rem 1rem 1rem rgba(0, 0, 0.1, 0.10);
    }
</style>