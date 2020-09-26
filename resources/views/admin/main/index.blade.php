@extends('admin.layout.master')

@section('headTitle')
    <title>داشبورد</title>
@endsection

@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">داشبورد</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">داشبورد</a></li>
            </ol>
        </div>
    </div>
@endsection


@section('content')

    @if(Session::has('welcomeAdmin'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('welcomeAdmin')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container-fluid">
        @include('partials.errors')
        @if(Session::has('addMembership'))
            <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('addMembership')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(Session::has('deleteMemberShip'))

            <div class="alert alert-danger alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('deleteMemberShip')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(Session::has('editMemberShip'))
            <div class="alert alert-danger alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('editMemberShip')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    @endif


    <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->


        <!-- Row - Charts  -->
        <div class="row">
            <div class="d-none d-md-block col-lg-9 col-xlg-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">آمار ماهانه بنت</h4>
                        <!-- <h6 class="card-subtitle">توضیحات بیشتر در مورد آمار</h6> -->
                        <div id="morris-area" style="height: 329px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">کابران ثبت نام کرده</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0">{{$userCount}}</h4></div>
                            <div class="ml-auto">
                                <div id="members"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">قراردادهای ثبت شده</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0">{{$ads}}</h4></div>
                            <div class="ml-auto">
                                <div id="contracts"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">درآمدهای ثبت شده</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0 ">{{$invoice}}</h4></div>
                            <div class="ml-auto">
                                <div id="incoming"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row - Charts  -->


        <!-- Row - Cards  -->
        <div class="row">
            <!-- ============================================================== -->
            <!-- Advertise-->
            <!-- ============================================================== -->
            <div class="col-md- 6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">آگهی ها</h4>
                        <h6 class="card-subtitle">4 شهر برتر در ثبت آگهی</h6>
                        <ul class="pt-3 px-3 sidebarnav">

                            @foreach($countOfCityAds as $key => $value)

                                <li class="mb-3">
                                    <span>{{$key}}</span>
                                    <span class="float-left">{{$value}}</span>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Balances and Pending balance-->
            <!-- ============================================================== -->
            <div class="col-md- 6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">موجودی کاربران</h4>
                        <h6 class="card-subtitle">موجودی حساب کاربران در حساب بانکی بنت</h6>
                        <div class="text-center mt-3 pt-1">
                            <h3>مجموع کل</h3>
                            <h3 class="toman">{{$usersBalance}}</h3>
                        </div>
                        <ul class="pt-3 px-3 sidebarnav">
                            @foreach($cityWithBalance as $key => $value)
                                <li class="mb-3">
                                    <span>{{$key}}</span>
                                    <span class="float-left toman">{{$value}}</span>
                                </li>
                            @endforeach
{{--                            <li class="mb-3">--}}
{{--                                <span>تهران</span>--}}
{{--                                <span class="float-left toman">10000</span>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Incoming-->
            <!-- ============================================================== -->
            <div class="col-md- 6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">درآمدها</h4>
                        <h6 class="card-subtitle">درآمد سالیانه بنت</h6>
                        <ul class="pt-3 px-3 sidebarnav">
                            <li class="mb-3">
                                <span>دریافتی از خسارت درجه اول</span>
                                <span class="float-left toman">{{$allKindOfinvoice[0]}}</span>
                            </li>
                            <li class="mb-3">
                                <span>دریافتی از خسارت درجه دوم</span>
                                <span class="float-left toman">{{$allKindOfinvoice[1]}}</span>
                            </li>
                            <li class="mb-3">
                                <span>بسته های معاملاتی</span>
                                <span class="float-left toman">{{$allKindOfinvoice[2]}}</span>
                            </li>
                            <li class="mb-3">
                                <span>درآمد لحظه ای</span>
                                <span class="float-left toman">{{$allKindOfinvoice[3]}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row - Cards  -->

        <!-- Row - Tables  -->
        <div class="row">
            <!-- column -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">کاربران اخیر</h4>
                        <h6 class="card-subtitle">نمایش 20 کاربر جدید</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام</th>
                                    <th>نام خانوادگی</th>
                                    <th>نام کاربری</th>
                                    <th>تاریخ ثبت نام</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i = 1)
                                @foreach($newUsersInfo as $info)

                                    <tr>
                                        <td><small>{{$i}}</small></td>
                                        <td><small>{{$info->name}}</small></td>
                                        <td><small>{{$info->family}}</small></td>
                                        <td><small>{{$info->username}}</small></td>
                                        {{--<td><small>{{jdate($info->created_at)}}</small></td>--}}
                                        <td><small>{{jdate($info->created_at)->format('%A, %d %B %y')}}</small></td>
                                        <td>
                                            <form class="btn-fixer" action="">
                                                <a href={{route('userInfo' , $info->id)}} class="label label-themecolor">مشاهده
                                                جزئیات</a>
                                                <button type="button" onclick="deleteUserData({{$info->id}})" class="label label-danger" data-toggle="modal" data-target=".bs-example-modal-sm" >پاک کن</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php($i += 1)
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- column -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">محصولات جدید</h4>
                        <h6 class="card-subtitle">20 محصول جدید ثبت شده</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام مدل</th>
                                    <th>برند</th>
                                    <th>کشور</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($newProducts as $product)
                                    <tr>
                                        <td><small>{{$i}}</small></td>
                                        <td><small>{{$product->model}}</small></td>
                                        <td><small>{{$product->brand}}</small></td>
                                        <td><small>{{$product->country}}</small></td>
                                        <td>
                                            <form class="btn-fixer" action="">
                                                <a href={{route('productInfo' , $product->id)}} class="label
                                                   label-themecolor">مشاهده
                                                جزئیات</a>

                                            </form>
                                        </td>
                                    </tr>
                                    @php($i++)
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row - Tables  -->


        <!-- Row - Tables  + From -->
        <div class="row">
            <!-- column -->
            <div class="col-12 col-md-4 col-lg-3">

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ساخت بسته معاملاتی جدید</h4>
                        <h6 class="card-subtitle">بسته های معاملاتی به محض ساخته شدن، در دسترس قرار میگیرند.</h6>
                        <form action="{{route("createMembership")}}" method="get" class="form-material m-t-40 row">
                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="membership-title">نام بسته</label>
                                <input name="title" id="membership-title" type="text"
                                       class="form-control form-control-line">
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="example-price">قیمت خرید</label>
                                <input name="price" id="membership-price" type="text"
                                       class="form-control form-control-line">
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="membership-revival">هزینه تمدید</label>
                                <input name="revival" id="membership-revival" type="text"
                                       class="form-control form-control-line">
                                <small class="help"> هزینه ی تمدید معمولا کمتر یا برابر قیمت خرید خواهد بود</small>
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="membership-dmg-one">مبلغ خسارت درجه یک</label>
                                <input name="dmg_level_one" id="membership-dmg-one" type="text"
                                       class="form-control form-control-line">
                                <small class="help"> مبلغ خسارت گرفته شده در فسخ قرارداد مرحله اول</small>
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="membership-dmg-two">درصد خسارت درجه دو</label>
                                <input name="dmg_level_two" id="membership-dmg-two" type="text"
                                       class="form-control form-control-line"
                                       maxlength="2">
                                <small class="help"> درصد محاسبه شده برای دریافت خسارت مرحله دوم</small>
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="membership-unity">آبونمان</label>
                                <input name="unity" id="membership-unity" type="text"
                                       class="form-control form-control-line">
                                <small class="help"> نرخ آبونمان لحظه ای بابت فعالیت کاربر</small>
                            </div>


                            <div class="col-12 m-t-20 text-left">
                                <small class="float-right pt-2 font-bold">تمامی قیمت ها به تومان می باشد.</small>
                                <button type="submit" class="btn btn-info waves-effect waves-light m-r-10 pb-0">ذخیره
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- column -->
            <div class="col-12 co-md-8 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">بسته های معاملاتی</h4>
                        <h6 class="card-subtitle">بسته هایی که همچنان توسط کاربران درحال استفاده هستند، قابل حذف
                            نیستند.</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام</th>
                                    <th>قیمت</th>
                                    <th>هزینه تمدید</th>
                                    <th>آبونمان</th>
                                    <th>هزینه خسارت درجه اول</th>
                                    <th>درصد خسارت درجه دوم</th>
                                    <th>تعداد بسته فعال</th>
                                    <th>وضعیت</th>
                                    <th>تغییر وضعیت / حذف</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($memberShipsWithCount as $memberShip)

                                    <tr>
                                        <td><small>{{$i}}</small></td>
                                        <td><small>{{$memberShip[0]->title}}</small></td>
                                        <td class="toman"><small>{{$memberShip[0]->price}}</small></td>
                                        <td class="toman"><small>{{$memberShip[0]->revival}}</small></td>
                                        <td class="toman"><small>{{$memberShip[0]->unity}}</small></td>
                                        <td class="toman"><small>{{$memberShip[0]->dmg_level_one}}</small></td>
                                        <td><small>{{$memberShip[0]->dmg_level_two}}</small></td>
                                        <td><small>{{$memberShip[0]->count}}</small></td>
                                        <td><small>@if($memberShip[0]->status == 1)
                                                    <button type="button" class="label label-success">فعال</button>
                                                @else
                                                    <button type="button" class="label label-danger">غیرفعال</button>
                                                @endif</small></td>
                                        <td>
                                            <form method="get" action="{{route('editMemberShip',$memberShip[0]->id)}}">
                                                @if($memberShip[0]->status == 1)
                                                    <button name="changeStatus" type="submit"
                                                            class="label label-danger ml-1">غیرفعال سازی
                                                    </button>

                                                @else
                                                    <button name="changeStatus" type="submit"
                                                            class="label label-success ml-1">فعال سازی
                                                    </button>
                                                @endif
                                                <button @if($memberShip[0]->count > 0) disabled @endif type="button"
                                                        onclick="deleteData({{$memberShip[0]->id}})"
                                                        class="label label-danger" data-toggle="modal"
                                                        data-target=".bs-example-modal-sm">پاک کن
                                                </button>

                                            </form>
                                        </td>
                                    </tr>
                                    @php($i+=1)
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- sample modal content -->

        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="deleted-modal"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mt-2" id="deleted-modal">حذف رکورد</h4>
                        <button type="button" class="close-modal float-right" data-dismiss="modal" aria-hidden="true">
                            ×
                        </button>
                    </div>

                    <form class="modal-body" method="get" action="" id="deleteForm">
                        <p class="text-right">آیا از حذف رکورد مطمئن هستید؟</p>
                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-sm btn-danger pb-0">حذفش کن</button>
                            <button type="button" class="btn btn-sm btn-info pb-0" data-toggle="modal"
                                    data-target=".bs-example-modal-sm">بیخیال
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("deleteMemberShip", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function deleteUserData(id) {
            var id = id;
            var url = '{{ route("deleteUser", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>



@endsection
