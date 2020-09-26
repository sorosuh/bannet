@extends('admin.layout.master')

@section('headTitle')
    <title>مشخصات کاربران</title>
@endsection

@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">مشخصات کاربران</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">مشخصات کاربران</a></li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    @if(Session::has('sendAgainRequest'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('sendAgainRequest')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('confirmInfo'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('confirmInfo')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <!-- Row  -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30"><img src="{{asset('admin/assets/avatar.png')}}"  class="img-circle" width="150" height="150"/>
                            <h4 class="card-title mt-3 mb-1">@if(isset($info[0]->username)){{$info[0]->username}}@endif</h4>
                            <small
                                class="d-block mb-3">@if(isset($info[0]->name)){{$info[0]->name ." ".$info[0]->family}}@endif</small>
                            <div class="row text-center justify-content-md-center">
                                <div class="col-4">
                                    <b class="font-bold">خریدها</b>
                                    <p class="mb-0">{{$userBuy}}</p>
                                </div>
                                <div class="col-4">
                                    <b class="font-bold">قراردادها</b>
                                    <p class="mb-0">{{$userContract}}</p>
                                </div>
                                <div class="col-4">
                                    <b class="font-bold">فروخته شده‌ها</b>
                                    <p class="mb-0">{{$userSold}}</p>
                                </div>
                            </div>
                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">

                        <div class="font-bold p-t-10 db"><strong>کد ملی</strong>
                            <br>
                            <p class="text-muted small ltr">@if(isset($info[0]->id_card)){{$info[0]->id_card}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db"><strong>شماره پروانه کسب</strong>
                            <br>
                            <p class="text-muted small ltr">@if(isset($info[0]->license_code)){{$info[0]->license_code}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db"><strong>تلفن همراه</strong>
                            <br>
                            <p class="text-muted small ltr">@if(isset($info[0]->phone)){{$info[0]->phone}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db"><strong>شهر</strong>
                            <br>
                            <p class="text-muted small">@if(isset($info[0]->city)){{$info[0]->city}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db"><strong>کد پستی</strong>
                            <br>
                            <p class="text-muted small ltr">@if(isset($info[0]->postal_code)){{$info[0]->postal_code}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db ltr"><strong>پست الکترونیک</strong>
                            <br>
                            <p class="text-muted small">@if(isset($info[0]->mail)){{$info[0]->mail}}@endif</p>
                        </div>

                        <div class="font-bold p-t-10 db"><strong>آدرس</strong>
                            <br>
                            <p class="text-muted small">@if(isset($info[0]->address)){{$info[0]->address}}@endif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column -->
            <div class="col-lg-9 col-md-6">
                <div class="card">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs profile-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#financial" role="tab">اطلاعات
                                حساب</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#uploades"
                                                role="tab">آپلودها</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoices"
                                                role="tab">فاکتورها</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ads" role="tab">آگهی و
                                قراردادها</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Financials -->
                        <div class="tab-pane active" id="financial" role="tabpanel">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-md-6 col-12 b-r"><strong>موجودی بنت</strong>
                                        <br>
                                        <p class="text-muted small toman">@if(isset($user_financial[0]->balance)){{$user_financial[0]->balance}}@endif</p>
                                    </div>
                                    <div class="col-md-6 col-12 b-r"><strong>موجودی حساب امن</strong>
                                        <br>
                                        <p class="text-muted small toman">@if(isset($user_financial[0]->pending_balance)){{$user_financial[0]->pending_balance}}@endif</p>
                                    </div>
                                    <div class="col-md-6 col-12 b-r"><strong>مبلغ درخواست عودت وجه</strong>
                                        <br>
                                        <p class="text-muted small">@if(isset($user_financial[0]->checkout)){{$user_financial[0]->checkout}}@endif</p>
                                    </div>
                                    <div class="col-md-6 col-12 b-r"><strong>شماره شبا</strong>
                                        <br>
                                        <p class="text-muted small">@if(isset($user_financial[0]->shabaa)){{$user_financial[0]->shabaa}}@endif</p>
                                    </div>
                                    <div class="col-md-6 col-12 b-r"><strong>شماره کارت</strong>
                                        <br>
                                        <p class="text-muted small">@if(isset($user_financial[0]->credit_card)){{$user_financial[0]->credit_card}}@endif</p>
                                    </div>
                                    <div class="col-md-3 col-12 b-r"><strong>بسته معاملاتی فعال</strong>
                                        <br>
                                        <p class="text-muted small">@if(isset($user_memberShip[0]->title)){{ $user_memberShip[0]->title}}@endif</p>
                                    </div>
                                    <div class="col-md-3 col-12 b-r"><strong>انقضا بسته معاملاتی</strong>
                                        <br>
                                        <p class="text-muted small">@if(isset($user_financial[0]->expired_at)){{Jdate($user_financial[0]->expired_at)->format('%A, %d %B %y')}}@endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Uploades -->

                        <div class="tab-pane" id="uploades">
                            <div class="card-body pb-0">
                                <form action="" method="" class="row">
                                    <div class="col-12 mb-4">
                                        <h3 class="text-right">چنانچه محتوای بالا با مشخصات ارسال نشده توسط کاربر مغایرت
                                            دارد، دوباره درخواست ارسال هویت دهید </h3>
                                        <!-- <p class="text-right text-warning">تا زمانیکه تمامی تصاویر آپلود نشده باشند، نمیتوانید کاربر را تایید نمایید.</p> -->
                                        @if( isset($isConfirmMedia[0]) && $isConfirmMedia[0]->license_code == 2 && $isConfirmMedia[0]->credit_card == 2 && $isConfirmMedia[0]->id_card == 2)
                                        <p class="text-right text-primary">شما سابقا این کاربر را تایید کرده اید.</p>
                                            @endif
                                    </div>

                                    <!-- When there aren't anything for show -->
                                    <!-- <p class="p-3 pt-4 bg-info radius text-white op-5"><i class="mdi mdi-information-outline ml-2"></i>تصویری آپلود نشده است</p> -->
                                    <!-- When there aren't anything for show -->

                                    <div class="col-12 col-lg-4">
                                        <!-- if you can't found images, you must use alternative image in your assets you show blank card -->
                                        @if(isset($nationalCart[0]))
                                        <img class="rounded"   src="{{$nationalCart[0]}}"
                                             alt="{{$nationalCart[1]}}"   style="height: auto; width: inherit;">
                                            @else
                                            <span>عکس موجود نیست</span>
                                        @endif
                                        <center class="mt-3 font-bold">تصویر کارت ملی</center>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        @if(isset($bankCart[0]))
                                        <img class="rounded"  src="{{$bankCart[0]}}"
                                             alt="{{$bankCart[1]}}"   style="height: auto; width: inherit;">
                                        @else
                                            <span>عکس موجود نیست</span>
                                        @endif
                                        <center class="mt-3 font-bold">تصویر کارت بانکی</center>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        @if(isset($licenseImage[0]))
                                        <img class="rounded"  src="{{$licenseImage[0]}}"
                                             alt="{{$licenseImage[1]}}"  style="height: auto; width: inherit;">
                                        @else
                                            <span>عکس موجود نیست</span>
                                        @endif
                                        <center class="mt-3 font-bold">تصویر پرونه کسب</center>
                                    </div>

                                </form>
                                {{--class="btn btn-info waves-effect waves-light pb-0"--}}
                                <div class="col-12 my-4 text-left">

                                    @if(isset($info[0]) && isset($nationalCart[0]) && isset($bankCart[0]) && isset($licenseImage[0]))
                                    <form style="display: inline" method="get"
                                          action="{{route('unconfirmMedia',[$info[0]->id,1])}}">
                                        <button type="submit"
                                                class="btn btn-info waves-effect waves-light pb-0"
                                                tabindex="13">تایید مشخصات
                                        </button>
                                    </form>

                                        <form style="display: inline" method="get"
                                              action="{{route('unconfirmMedia',[$info[0]->id,0])}}">
                                            <button type="submit"
                                                    class="btn bg-white border-info text-info waves-effect waves-light m-r-10 pb-0"
                                                    tabindex="13">درخواست ارسال دوباره
                                            </button>
                                        </form>
                                    @elseif(isset($info[0]))

                                    <!-- if all image couldn't loaded here, you must disable first button -->
                                    <form style="display: inline" method="get"
                                          action="{{route('unconfirmMedia',[$info[0]->id,0])}}">
                                        <button type="submit"
                                                class="btn bg-white border-info text-info waves-effect waves-light m-r-10 pb-0"
                                                tabindex="13">درخواست ارسال دوباره
                                        </button>
                                    </form>
                                        @endif
                                </div>
                            </div>
                        </div>

                        <!-- Invoices -->
                        <div class="tab-pane" id="invoices" role="tabpanel">
                            <div class="card-body pb-0">
                                <!-- When there aren't anything for show -->
                                <!-- <p class="p-3 pt-4 bg-info radius text-white op-5"><i class="mdi mdi-information-outline ml-2"></i>اطلاعاتی برای نمایش وجود ندارد.</p> -->
                                <!-- When there aren't anything for show -->

                                <div class="table-responsive">
                                    <table id="demo-foo-addrow" class="table" data-page-size="50">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان فاکتور</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت تراکنش</th>
                                            <th>تاریخ چاپ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($i=1)
                                        @foreach($userInvoices as $invoice)
                                            <tr>
                                                <td><small class="d-block mt-2">{{$i}}</small></td>
                                                <td><small class="d-block mt-2">{{$invoice->title}}</small></td>
                                                <td><small class="d-block mt-2">{{$invoice->price}}</small></td>
                                                @if($invoice->status == 2)
                                                    <td><small class="label label-light-danger mt-1 pt-2">کسر از
                                                            حساب</small></td>
                                                @elseif($invoice->status == 1)
                                                    <td><small class="label label-light-primary mt-1 pt-2">افزایش
                                                            موجودی</small></td>
                                                @else<td></td>
                                                @endif

                                                <td><small
                                                        class="d-block toman mt-2">@if(isset($invoice->created_at)){{jdate($invoice->created_at)->format('%A, %d %B %y')}}@else
                                                            <span>Undefined</span>@endif</small></td>
                                            </tr>
                                            @php($i++)
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ADS and Contracts -->
                        <div class="tab-pane" id="ads" role="tabpanel">
                            <div class="card-body pb-0">
                                <!-- When there aren't anything for show -->
                                <!-- <p class="p-3 pt-4 bg-info radius text-white op-5"><i class="mdi mdi-information-outline ml-2"></i>اطلاعاتی برای نمایش وجود ندارد.</p> -->
                                <!-- When there aren't anything for show -->

                                <div class="table-responsive">
                                    <table id="demo-foo-addrow" class="table" data-page-size="50">
                                        <thead>
                                        <tr>
                                            <th class="small">#</th>
                                            <th class="small">نام مدل کالا</th>
                                            <th class="small">کاندیدها</th>
                                            <th class="small">نام کامل برنده</th>
                                            <th class="small">قیمت توافق شده</th>
                                            <th class="small">قیمت فروشنده</th>
                                            <th class="small">تعداد</th>
                                            <th class="small">سال تولید</th>
                                            <th class="small">زمان انتظار ارسال</th>
                                            <th class="small">وضعیت</th>
                                            <th class="small">تاریخ انقضا</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($i=1)
                                        @foreach($userAdsInfo as $ads)
                                            <tr>
                                                <td><small class="d-block mt-2">{{$i}}</small></td>
                                                <td><small class="d-block mt-2">{{$ads[0]->model}}</small></td>
                                                <td><small class="d-block mt-2">{{$ads[0]->candidate}} نفر</small></td>
                                                <td><small class="label label-light-warning mt-1 pt-2">@if(isset($ads[1][0])){{$ads[1][0]->name ."    ". $ads[1][0]->family}}@elseمشخص نشده@endif</small>
                                                </td>
                                                <td><small class="label label-light-primary mt-1 pt-2">{{$ads[0]->bid_price}}</small>
                                                </td>
                                                <td><small class="d-block mt-2 toman">{{$ads[0]->cost}}</small></td>
                                                <td><small class="d-block mt-2">{{$ads[0]->count}}</small></td>
                                                <td><small class="d-block mt-2">{{$ads[0]->year}}</small></td>
                                                <td><small class="d-block mt-2">{{$ads[0]->shipment_day}} روز</small></td>

                                                <td> @if( isset($ads[0]) && $ads[0]->status == 0) <small class="label label-light-megna mt-1 pt-2">اگهی</small> @elseif($ads[0]->status == 1)  <small class="label label-light-warning mt-1 pt-2">شروع شده</small>@elseif($ads[0]->status == 2) <small class="label label-light-info mt-1 pt-2">انتظار</small> @elseif($ads[0]->status == 4)  <small class="label label-light-danger mt-1 pt-2">کنسل شده</small>@endif</td>

                                                <td><small class="d-block mt-2">@if(isset($ads[0]->expired_at)){{Jdate($ads[0]->expired_at)->format('%A, %d %B %y')}}@else نامحدود @endif</small></td>
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
            </div>


        </div>
        <!-- Row  -->

        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
@endsection
