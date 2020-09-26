@extends('admin.layout.master')

@section('headTitle')
    <title>صفحه مشخصات محصول</title>
@endsection

@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">انبار</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">مشخصات محصولات</a></li>
            </ol>
        </div>
    </div>
@endsection



@section('content')
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
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
                        <h4 class="card-title">میانگین قیمت </h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0 toman">{{$avgPrice}}</h4></div>
                            <div class="ml-auto">
                                <div id="price-avg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">مجموع آگهی‌ها و قراردادها</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0 ">{{$sumOfBid}}</h4></div>
                            <div class="ml-auto">
                                <div id="count-ads"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">قراردادهای موفق</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0 ">{{$successBid}}</h4></div>
                            <div class="ml-auto">
                                <div id="success-ads"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">آگهی های ناموفق</h4>
                        <div class="d-flex">
                            <div class="align-self-center">
                                <h4 class="font-medium m-b-0 ">{{$failBid}}</h4></div>
                            <div class="ml-auto">
                                <div id="failed-ads"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Row  -->

        <!-- Row  -->
        <div class="row">
            <!-- column - form -->
            <div class="col-12 col-md-5">
                <!-- Column -->
                <div class="card card-default">

                    <div class="card-header">
                        <div class="card-actions float-left pt-2">
                            <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                            <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                        </div>
                        <h4 class="card-title pt-2 mb-0">تصاویر کالا</h4>
                    </div>

                    <div class="card-body collapse show">

                        <div id="myCarousel" class="carousel slide" data-ride="carousel">

                            <!-- Carousel items -->
                            <div class="carousel-inner" style="height: 500px">

                                @php($sw=0)
                                @foreach($mediaPath as $path)

                                    @if($sw==0)
                                    <div class="carousel-item flex-column text-center active">
                                        <img src="{{$path}}" alt="brand name"
                                             style="width: auto; height: 500px;">
                                    </div>
                                        @php($sw=1)
                                        @elseif($sw=1)
                                        <div class="carousel-item flex-column text-center ">
                                            <img src="{{$path}}" alt="brand name"
                                                 style="width: auto; height: 500px;">
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ویرایش نمونه کالا</h4>
                        <form class="form-material m-t-40 row" method="POST"
                              action="{{route('editProduct' , $product->id)}}">
                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="pro-model">اسم کامل</label>
                                <input name="model" value="{{$product->model}}" id="pro-model" type="text"
                                       class="form-control form-control-line" tabindex="1"
                                       required>
                            </div>

                            <div class="form-group col-lg-6 col-12 m-t-2">
                                <label class="mb-0" for="pro-designed">طرح گل</label>
                                <input name="designed" value="{{$product->designed}}" id="pro-designed" type="text"
                                       class="form-control form-control-line" tabindex="2"
                                       required>
                            </div>

                            <div class="form-group col-lg-6 col-12 m-t-2" tabindex="3">
                                <label class="mb-0">نوع تایر</label>
                                <select name="type" class="form-control" name="type">
                                    <option value="" disabled>یک مدل تایر انتخاب کنید</option>
                                    <option @if($product->type ==1) selected="selected" @endif  value="1">تایر خودرو
                                        سواری
                                    </option>
                                    <option @if($product->type ==2) selected="selected" @endif value="2">تایر خودرو
                                        صنعتی
                                    </option>
                                    <option @if($product->type ==3) selected="selected" @endif value="3">تایر
                                        موتورسیکلت
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-model">کشور</label>
                                <input name="country" value="{{$product->country}}" id="pro-model" type="text"
                                       class="form-control form-control-line" tabindex="4"
                                       required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-brand">برند</label>
                                <input name="brand" value="{{$product->brand}}" id="pro-brand" type="text"
                                       class="form-control form-control-line" tabindex="3"
                                       required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-color">رنگ</label>
                                <input name="color" value="{{$product->color}}" id="pro-color" type="text"
                                       class="form-control form-control-line" tabindex="4"
                                       required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-color">تیوبلکس</label>
                                <input name="tubeless" value="{{$product->tubeless}}" id="pro-color" type="text"
                                       class="form-control form-control-line" tabindex="7"
                                       required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-width">پهنا</label>
                                <input name="width" value="{{$product->width}}" id="pro-width" type="text"
                                       class="form-control form-control-line" maxlength="8" tabindex="5" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-diameter">قطر رینگ</label>
                                <input name="diameter" value="{{$product->diameter}}" id="pro-diameter" type="text"
                                       class="form-control form-control-line" maxlength="9" tabindex="6" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-height">ارتفاع دیوار (فاق)</label>
                                <input name="height" value="{{$product->height}}" id="pro-height" type="text"
                                       class="form-control form-control-line"
                                       maxlength="10"
                                       tabindex="7" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-tire-height"> ارتفاع تایر</label>
                                <input name="tire_height" value="{{$product->tire_height}}" id="pro-tire-height"
                                       type="text"
                                       class="form-control form-control-line"
                                       maxlength="4" tabindex="8" required>
                            </div>

                            <div class="form-group col-lg-2 col-12 m-t-2">
                                <label class="mb-0" for="pro-speed">شاخص بار</label>
                                <input name="weight" value="{{$product->weight}}" id="pro-speed" type="text"
                                       class="form-control form-control-line"
                                       maxlength="12"
                                       tabindex="9" required>
                            </div>

                            <div class="form-group col-lg-2 col-12 m-t-2">
                                <label class="mb-0" for="pro-speed">شاخص سرعت</label>
                                <input name="speed" value="{{$product->speed}}" id="pro-speed" type="text"
                                       class="form-control form-control-line" maxlength="3"
                                       tabindex="9" required>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-12 mt-4 pt-2">
                                <input @if($product->front_using == 0) checked @endif type="checkbox"
                                       id="pro-front-using" tabindex="11"
                                       @if($product->front_using == 1) checked @endif/>
                                <label for="pro-front-using">قابلیت استفاده در جلو</label>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-12 mt-4 pt-2">
                                <input type="checkbox" id="pro-back-using" tabindex="12"
                                       @if($product->back_using == 1) checked @endif/>
                                <label for="pro-back-using">قابلیت استفاده در عقب</label>
                            </div>

                            <div class="d-lg-none"><br><br><br><br></div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="keyword">عبارات کانونی</label>
                                <input @if(isset($seo->keywords))value="{{$seo->keywords}}" @endif name="keywords"
                                       id="pro-weight" type="text"
                                       class="form-control form-control-line" tabindex="10"
                                       required>
                                <small>عبارات کانونی خود را با ویرگول جدا کنید | حداقل عبارات کلیدی پیشنهادی: 5
                                    عدد</small>
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="keyword">توضیحات متا</label>

                                <input @if(isset($seo->desc))value="{{$seo->desc}}" @endif name="desc" id="pro-weight"
                                       type="text"
                                       class="form-control form-control-line" maxlength="60"
                                       tabindex="10" required>
                                <small>حداکثر حروف مجاز: 60 حرف</small>
                            </div>

                            <!-- Editted Form -->
                            <div class="col-12 m-t-20 text-left">

                                <div class="float-right">
                                    <small class="d-block text-right font-bold main-pic">ابتدا تصویر اصلی را انتخاب نمایید</small>
                                    <small class="d-none  font-bold other-pics">
                                        هم اکنون میتوانید تصاویر دیگر کالا را انتخاب نمایید &nbsp; |
                                        <span id="toggle-pics" class="text-primary pr-2" style="cursor:pointer">انتخاب دوباره تصویر اصلی</span>
                                    </small>

                                    <small class="file-info d-block text-right text-warning font-bold pt-1">ابعاد تصویر: 200x200 پیکسل</small>
                                </div>

                                <input type="file" id="main-upload" class="d-none">
                                <label for="main-upload" class="btn text-info border-info pb-0 mt-2">آپلود تصویر اصلی</label>

                                <input type="file" id="file-upload" text-info class="d-none" multiple>
                                <label for="file-upload" class="btn text-info border-info pb-0 mt-2 d-none">آپلود تصاویر دیگر</label>

                                <button type="submit" class="btn btn-info waves-effect waves-light m-r-10 pb-0" tabindex="18">بروزرسانی</button>
                            </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>





@endsection

@section('js')

    <script>

        $(function () {
            "use strict";
            // ==============================================================
            // sparkline chart
            // ==============================================================
            var sparklineLogin = function() {
                $('#price-avg').sparkline([
                    {{floor($avgPriceInMonth[1])}},
                    {{floor($avgPriceInMonth[2])}},
                    {{floor($avgPriceInMonth[3])}},
                    {{floor($avgPriceInMonth[4])}},
                    {{floor($avgPriceInMonth[5])}},
                    {{floor($avgPriceInMonth[6])}},
                    {{floor($avgPriceInMonth[7])}},
                    {{floor($avgPriceInMonth[8])}},
                    {{floor($avgPriceInMonth[9])}},
                    {{floor($avgPriceInMonth[10])}},
                    {{floor($avgPriceInMonth[11])}},
                    {{floor($avgPriceInMonth[12])}}
                ], {
                    type: 'bar',
                    width: '100%',
                    height: '40',
                    barWidth: '4',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#26c6da'
                });
                $('#count-ads').sparkline([
                    {{$sumOfBidInMonth[1]}},
                    {{$sumOfBidInMonth[2]}},
                    {{$sumOfBidInMonth[3]}},
                    {{$sumOfBidInMonth[4]}},
                    {{$sumOfBidInMonth[5]}},
                    {{$sumOfBidInMonth[6]}},
                    {{$sumOfBidInMonth[7]}},
                    {{$sumOfBidInMonth[8]}},
                    {{$sumOfBidInMonth[9]}},
                    {{$sumOfBidInMonth[10]}},
                    {{$sumOfBidInMonth[11]}},
                    {{$sumOfBidInMonth[12]}}
                ], {
                    type: 'bar',
                    width: '100%',
                    height: '40',
                    barWidth: '4',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#ef5350'
                });
                $('#success-ads').sparkline([
                    {{$successBidInMonth[1]}},
                    {{$successBidInMonth[2]}},
                    {{$successBidInMonth[3]}},
                    {{$successBidInMonth[4]}},
                    {{$successBidInMonth[5]}},
                    {{$successBidInMonth[6]}},
                    {{$successBidInMonth[7]}},
                    {{$successBidInMonth[8]}},
                    {{$successBidInMonth[9]}},
                    {{$successBidInMonth[10]}},
                    {{$successBidInMonth[11]}},
                    {{$successBidInMonth[12]}}
                ], {
                    type: 'bar',
                    width: '100%',
                    height: '40',
                    barWidth: '4',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#7460ee'
                });
                $('#failed-ads').sparkline([
                    {{$failBidInMonth[1]}},
                    {{$failBidInMonth[2]}},
                    {{$failBidInMonth[3]}},
                    {{$failBidInMonth[4]}},
                    {{$failBidInMonth[5]}},
                    {{$failBidInMonth[6]}},
                    {{$failBidInMonth[7]}},
                    {{$failBidInMonth[8]}},
                    {{$failBidInMonth[9]}},
                    {{$failBidInMonth[10]}},
                    {{$failBidInMonth[11]}},
                    {{$failBidInMonth[12]}}
                ], {
                    type: 'bar',
                    width: '100%',
                    height: '40',
                    barWidth: '4',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#007bff'
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

            $('#main-upload').change(function() {
                $('#main-upload + label, #file-upload + label').toggleClass('d-none');
                $('.main-pic, .other-pics').toggleClass('d-block d-none');
            })

            $('#toggle-pics').click(function() {
                $('#main-upload + label, #file-upload + label').toggleClass('d-none');
                $('.main-pic, .other-pics').toggleClass('d-block d-none');
                $('.file-info').html('ابعاد تصویر: 200x200 پیکسل');
            })

            $('#file-upload').change(function(e) {
                let fileName = e.target.files.length;
                $('.file-info').html(`در مجموع ${fileName + 1} تصویر انتخاب شده است`);
            })
        })


    </script>

@endsection
