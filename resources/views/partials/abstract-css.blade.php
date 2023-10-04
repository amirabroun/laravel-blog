<link rel="stylesheet"  href="{{ url('/css/bootstrap.css') }}">

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

    .form-input {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        background-clip: padding-box;
        border-radius: 0.25rem;
        font-family: inherit;
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
        border-radius: 0.25rem;
        font-family: inherit;
        background-color: #fff;
        width: 100%;
        border: none;
        display: block;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
    }
    .skill-box {
        width: 100%;
        margin: 25px 0;
    }

    .skill-box .title {
        display: block;
        font-size: 12px;    
        font-weight: 600;
        color: #333;
    }

    .skill-box .skill-bar {
        height: 8px;
        width: 100%;
        border-radius: 6px;
        margin-top: 6px;
        background: rgba(0, 0, 0, 0.1);
    }

    .skill-bar .skill-per {
        position: relative;
        display: block;
        height: 100%;
        width: 0%;
        border-radius: 6px;
        background: #4070f4;
        animation: progress 1.5s ease-in-out forwards;
    }

    @keyframes progress {
        0% {
            width: 0;
            opacity: 1;
        }

        100% {
            opacity: 1;
        }
    }

</style>

