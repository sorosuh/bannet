<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta name="description" content="">
    <meta name="author" content=""> -->
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    @yield('headTitle')
    <!-- Bootstrap Core CSS -->
    <link href="/admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="/admin/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="/admin/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="/admin/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="/admin/plugins/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/admin/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="/admin/css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header card-no-border">


<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">
                    <!-- Logo icon -->
                    <b>
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span>
                            <!-- dark Logo text -->
                            <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->
                            <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" />
                        </span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto mt-md-0">
                    <!-- This is  -->
                    <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                                            href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
                    <li class="nav-item m-l-10"><a
                            class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                            href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                </ul>
                <!-- ============================================================== -->
                <!-- User profile -->
                <!-- ============================================================== -->
                <ul class="navbar-nav my-lg-0">
                    <!-- ============================================================== -->
                    <!-- Profile -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src="/admin/assets/avatar.png" alt="user" class="profile-pic"/></a>
                        <div class="dropdown-menu animated slideInUp">
                            <ul class="dropdown-user">
                                    <li><a href="{{route('logout')}}"><i class="fa fa-power-off ml-1"></i> خارج شدن از حساب</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <div class="user-profile">
                <!-- is empty for now -->
            </div>

            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <!-- <li class="nav-devider"></li> -->
                    <li class="nav-small-cap">پیشخوان</li>
                    <li>
                        <a class="waves-effect waves-dark" href={{route('main')}}><i
                                class="mdi mdi-chart-areaspline"></i><span class="hide-menu">در یک نگاه </span></a>
                    </li>
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-multiple"></i><span class="hide-menu">افراد</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href={{route('users')}}>کاربران</a></li>
                            <li><a href={{route('adminsInfo')}}>مدیران</a></li>
                        </ul>
                    </li>
                    <li><a class="waves-effect waves-dark" href={{route('product')}}><i
                                class="mdi mdi-database"></i><span class="hide-menu"><span>انبار</span></a></li>
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Right Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb-->
        <!-- ============================================================== -->
    {{--        <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor pt-2">تیتر صفحه ی رندر شده</h3>
                </div>
                <div class="col-md-7 align-self-center pt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">اسم منوی اصلی</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">اسم زیرمنو</a></li>
                    </ol>
                </div>
            </div>--}}
    @yield('pageTitle')
    <!-- ============================================================== -->
        <!-- End Bread crumb -->
        <!-- ============================================================== -->

    @yield('content')


    <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © 2021 Bannet Pannel by Mabna
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="/admin/plugins/bootstrap/js/popper.min.js"></script>
<script src="/admin/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="/admin/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="/admin/js/waves.js"></script>
<!--Menu sidebar -->
<script src="/admin/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="/admin/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
<!--Custom JavaScript -->
<script src="/admin/js/custom.min.js"></script>
{{--<script src="/admin/js/script-product.js"></script>--}}
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!--sparkline JavaScript -->
<script src="/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- chartist chart -->
<script src="/admin/plugins/chartist-js/dist/chartist.min.js"></script>
<script src="/admin/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
<!--morris JavaScript -->
<script src="/admin/plugins/raphael/raphael-min.js"></script>
<script src="/admin/plugins/morrisjs/morris.min.js"></script>


<!-- My JavaScript -->
{{--<script src="/admin/js/script.js">--}}
{{--</script>--}}

@if(isset($MonthlyBannet))
<script>

    /*
    Template Name: Admin Press Admin
    Author: Themedesigner
    Email: niravjoshi87@gmail.com
    File: js
    */


    $(function () {

        // ==============================================================
        // SALES DIFFERENCE
        // ==============================================================
        Morris.Area({
            element: 'morris-area',
            data: [{
                period: '1',
                SiteA: {{($MonthlyBannet[1][0])}},
                SiteB: {{$MonthlyBannet[1][1]}},
                SiteC: {{$MonthlyBannet[1][2]}},
            }, {
                period: '2',
                SiteA: {{$MonthlyBannet[2][0]}},
                SiteB: {{$MonthlyBannet[2][1]}},
                SiteC: {{$MonthlyBannet[2][2]}},
            }, {
                period: '3',
                SiteA: {{$MonthlyBannet[3][0]}},
                SiteB: {{$MonthlyBannet[3][1]}},
                SiteC: {{$MonthlyBannet[3][2]}},
            }, {
                period: '4',
                SiteA: {{$MonthlyBannet[4][0]}},
                SiteB: {{$MonthlyBannet[4][1]}},
                SiteC: {{$MonthlyBannet[4][2]}},
            }, {
                period: '5',
                SiteA: {{$MonthlyBannet[5][0]}},
                SiteB: {{$MonthlyBannet[5][1]}},
                SiteC: {{$MonthlyBannet[5][2]}},
            }, {
                period: '6',
                SiteA: {{$MonthlyBannet[6][0]}},
                SiteB: {{$MonthlyBannet[6][1]}},
                SiteC: {{$MonthlyBannet[6][2]}},
            },
                {
                    period: '7',
                    SiteA: {{$MonthlyBannet[7][0]}},
                    SiteB: {{$MonthlyBannet[7][1]}},
                    SiteC: {{$MonthlyBannet[7][2]}},
                },
                {
                    period: '8',
                    SiteA: {{$MonthlyBannet[8][0]}},
                    SiteB: {{$MonthlyBannet[8][1]}},
                    SiteC: {{$MonthlyBannet[8][2]}},
                },
                {
                    period: '9',
                    SiteA: {{$MonthlyBannet[9][0]}},
                    SiteB: {{$MonthlyBannet[9][1]}},
                    SiteC: {{$MonthlyBannet[9][2]}},
                },
                {
                    period: '10',
                    SiteA: {{$MonthlyBannet[10][0]}},
                    SiteB: {{$MonthlyBannet[10][1]}},
                    SiteC: {{$MonthlyBannet[10][2]}},
                },
                {
                    period: '11',
                    SiteA: {{$MonthlyBannet[11][0]}},
                    SiteB: {{$MonthlyBannet[1][1]}},
                    SiteC: {{$MonthlyBannet[11][2]}},
                },
                {
                    period: '12',
                    SiteA: {{$MonthlyBannet[12][0]}},
                    SiteB: {{$MonthlyBannet[12][1]}},
                    SiteC: {{$MonthlyBannet[12][2]}},
                }],
            xkey: 'period',
            ykeys: ['SiteA', 'SiteB', 'SiteC'],
            labels: ['کاربران', 'قراردادها','درآمدهای ثبت شده'],
            pointSize: 0,
            fillOpacity: 0.4,
            pointStrokeColors:['#b4becb', '#01c0c8', '#01c0c5'],
            behaveLikeLine: true,
            gridLineColor: '#e0e0e0',
            lineWidth: 0,
            smooth: false,
            hideHover: 'auto',
            lineColors: ['#b4becb', '#01c0c8', '#01c0c5'],
            resize: true,
            xLabelFormat: function(x) {
                let year = x.getFullYear();
                return checkMonth(year);
            }

        });

        function checkMonth(x) {
            if(x == 1901) { return 'فروردین' }
            else if(x == 1902) { return 'اردیبهشت' }
            else if(x == 1903) { return 'خرداد' }
            else if(x == 1904) { return 'تیر' }
            else if(x == 1905) { return 'مرداد' }
            else if(x == 1906) { return 'شهریور' }
            else if(x == 1907) { return 'مهر' }
            else if(x == 1908) { return 'آبان' }
            else if(x == 1909) { return 'آذر' }
            else if(x == 1910) { return 'دی' }
            else if(x == 1911) { return 'بهمن' }
            else if(x == 1912) { return 'اسفند' }
        }

        // ==============================================================
        // sparkline chart
        // ==============================================================
        var sparklineLogin = function() {
            $('#members').sparkline([
                {{count($userCountInMonth[1])}},
                {{count($userCountInMonth[2])}},
                {{count($userCountInMonth[3])}},
                {{count($userCountInMonth[4])}},
                {{count($userCountInMonth[5])}},
                {{count($userCountInMonth[6])}},
                {{count($userCountInMonth[7])}},
                {{count($userCountInMonth[8])}},
                {{count($userCountInMonth[9])}},
                {{count($userCountInMonth[10])}},
                {{count($userCountInMonth[11])}},
                {{count($userCountInMonth[12])}}
                ], {
                type: 'bar',
                width: '100%',
                height: '40',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#26c6da'
            });
            $('#contracts').sparkline([
                {{count($adsCountInMonth[1])}},
                {{count($adsCountInMonth[2])}},
                {{count($adsCountInMonth[3])}},
                {{count($adsCountInMonth[4])}},
                {{count($adsCountInMonth[5])}},
                {{count($adsCountInMonth[6])}},
                {{count($adsCountInMonth[7])}},
                {{count($adsCountInMonth[8])}},
                {{count($adsCountInMonth[9])}},
                {{count($adsCountInMonth[10])}},
                {{count($adsCountInMonth[11])}},
                {{count($adsCountInMonth[12])}}

            ], {
                type: 'bar',
                width: '100%',
                height: '40',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#ef5350'
            });
            $('#incoming').sparkline([
                {{count($invoiceInMonth[1])}},
                {{count($invoiceInMonth[2])}},
                {{count($invoiceInMonth[3])}},
                {{count($invoiceInMonth[4])}},
                {{count($invoiceInMonth[5])}},
                {{count($invoiceInMonth[6])}},
                {{count($invoiceInMonth[7])}},
                {{count($invoiceInMonth[8])}},
                {{count($invoiceInMonth[9])}},
                {{count($invoiceInMonth[10])}},
                {{count($invoiceInMonth[11])}},
                {{count($invoiceInMonth[12])}}
            ], {
                type: 'bar',
                width: '100%',
                height: '40',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#7460ee'
            });
        }
        var sparkResize;

        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();
    });


    $(document).ready(function() {
        $('.toman').each(function() {
            let num = $(this).html();
            if(typeof num === 'undefined' || num == null) return;
            if(num == 0) return 'رایگان';
            let number = typeof num === "number" ? num.toString() : num;
            let toman =  number.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1"+',') + ' تومان';
            $(this).html(toman);
        })
    })


</script>
@endif

@yield('js')

</body>

</html>
