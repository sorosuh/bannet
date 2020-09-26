
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> <title>ورود | پنل مدیران بنت</title>
    <link href="/admin/css/login.min.css" rel="stylesheet">

</head>
<body>
<div class="wrapper">
    <div class="container">
        @if(Session::has('errorInLogin'))
            <div class="alert alert-warning alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('errorInLogin')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
            @if(Session::has('logout'))
                <div class="alert alert-warning alert-dismissible fade show" style="margin: 10px 31px">
                    <strong>{{session('logout')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        <h1>خوش آمدید</h1>

        <form class="form" method="get" action="{{route('login')}}" >
            <input name="username" type="text" placeholder="نام کاربری">
            <input name="password" type="password" placeholder="گذرواژه">
            <button type="submit" id="login-button">ورود</button>
            <!-- warning for form -->
            <!-- <p class="warning-from">گذرواژه یا نام کاربری وارد شده، اشتباه است.</p> -->
        </form>

    </div>

    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="/admin/plugins/jquery/jquery.min.js"></script>
<script src="/admin/plugin/script-login.js"></script>
</body>
