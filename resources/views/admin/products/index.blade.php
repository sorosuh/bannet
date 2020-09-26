@extends('admin.layout.master')

@section('headTitle')
    <title>صفحه محصولات</title>
@endsection

@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">انبار</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)"> محصولات</a></li>
            </ol>
        </div>
    </div>
@endsection


@section('content')

    @include('partials.errors')
    @if(Session::has('addProduct'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('addProduct')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('editProduct'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('editProduct')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('deleteProduct'))
        <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('deleteProduct')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container-fluid">
        <!-- Row - Tables  -->
        <div class="row">
            <!-- column -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ثبت نمونه کالا</h4>
                        <form method="post" action="{{route('addProduct')}}" class="form-material m-t-40 row" enctype="multipart/form-data">
                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="pro-model">اسم کامل</label>
                                <input name="model" id="pro-model" type="text" class="form-control form-control-line"
                                       tabindex="1" required>
                            </div>

                            <div class="form-group col-lg-6 col-12 m-t-2">
                                <label class="mb-0" for="pro-designed">طرح گل</label>
                                <input name="designed" id="pro-designed" type="text"
                                       class="form-control form-control-line" tabindex="2" required>
                            </div>

                            <div class="form-group col-lg-6 col-12 m-t-2" tabindex="3">
                                <label class="mb-0">نوع تایر</label>
                                <select name="type" class="form-control" name="type">
                                    <option value="" disabled>یک مدل تایر انتخاب کنید</option>
                                    <option value="1">تایر خودرو سواری</option>
                                    <option value="2">تایر خودرو صنعتی</option>
                                    <option value="3">تایر موتورسیکلت</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-model">کشور</label>
                                <input name="country" id="pro-model" type="text" class="form-control form-control-line"
                                       tabindex="4" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-brand">برند</label>
                                <input name="brand" id="pro-brand" type="text" class="form-control form-control-line"
                                       tabindex="5" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-color">رنگ</label>
                                <input name="color" id="pro-color" type="text" class="form-control form-control-line"
                                       tabindex="6" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-color">تیوبلکس</label>
                                <input name="tubeless" id="pro-color" type="text" class="form-control form-control-line"
                                       tabindex="7" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-width">پهنا</label>
                                <input name="width" id="pro-width" type="text" class="form-control form-control-line"
                                       maxlength="8" tabindex="5" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-diameter">قطر رینگ</label>
                                <input name="diameter" id="pro-diameter" type="text"
                                       class="form-control form-control-line" maxlength="9" tabindex="6" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-height">ارتفاع دیوار (فاق)</label>
                                <input name="height" id="pro-height" type="text" class="form-control form-control-line"
                                       maxlength="10" tabindex="7" required>
                            </div>

                            <div class="form-group col-lg-3 col-12 m-t-2">
                                <label class="mb-0" for="pro-tire-height"> ارتفاع تایر</label>
                                <input name="tire_height" id="pro-tire-height" type="text"
                                       class="form-control form-control-line" maxlength="11" tabindex="8" required>
                            </div>

                            <div class="form-group col-lg-2 col-12 m-t-2">
                                <label class="mb-0" for="pro-speed">شاخص بار</label>
                                <input name="weight" id="pro-speed" type="text" class="form-control form-control-line"
                                       maxlength="12" tabindex="9" required>
                            </div>

                            <div class="form-group col-lg-2 col-12 m-t-2">
                                <label class="mb-0" for="pro-weight">شاخص سرعت</label>
                                <input name="speed" id="pro-weight" type="text" class="form-control form-control-line"
                                       maxlength="13" tabindex="10" required>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-12 mt-4 pt-2">
                                <input name="front_using" type="checkbox" id="pro-front-using" tabindex="14"/>
                                <label for="pro-front-using">قابلیت استفاده در جلو</label>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-12 mt-4 pt-2">
                                <input name="back_using" type="checkbox" id="pro-back-using" tabindex="15"/>
                                <label for="pro-back-using">قابلیت استفاده در عقب</label>
                            </div>

                            <div class="d-lg-none"><br><br><br><br></div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="keyword">عبارات کانونی</label>
                                <input name="keywords" id="pro-weight" type="text"
                                       class="form-control form-control-line" tabindex="16" required>
                                <small>عبارات کانونی خود را با ویرگول جدا کنید | حداقل عبارات کلیدی پیشنهادی: 5
                                    عدد</small>
                            </div>

                            <div class="form-group col-12 m-t-2">
                                <label class="mb-0" for="keyword">توضیحات متا</label>
                                <input name="desc" id="pro-weight" type="text" class="form-control form-control-line"
                                       maxlength="60" tabindex="17" required>
                                <small>حداکثر حروف مجاز: 60 حرف</small>
                            </div>




                                <div class="col-12 m-t-20 text-left">
                                    <div class="float-right">
                                        <small class="d-block text-right font-bold main-pic">ابتدا تصویر اصلی را انتخاب نمایید</small>
                                        <small class="d-none  font-bold other-pics">
                                            هم اکنون میتوانید تصاویر دیگر کالا را انتخاب نمایید &nbsp; |
                                            <span id="toggle-pics" class="text-primary pr-2" style="cursor:pointer">انتخاب دوباره تصویر اصلی</span>
                                        </small>

                                        <small class="file-info d-block text-right text-warning font-bold pt-1">ابعاد تصویر: 200x200 پیکسل</small>
                                    </div>

                                    <input type="file" name="mainMedia" id="main-upload" class="d-none" enctype="multipart/form-data">
                                    <label for="main-upload" class="btn text-info border-info pb-0 mt-2">آپلود تصویر اصلی</label>

                                    <input type="file" name="otherMedia[]" id="file-upload" text-info class="d-none"  enctype="multipart/form-data" multiple >
                                    <label for="file-upload" class="btn text-info border-info pb-0 mt-2 d-none">آپلود تصاویر دیگر</label>

                                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10 pb-0" tabindex="18">بروزرسانی</button>
                                </div>



                        </form>
                    </div>
                </div>
            </div>
            <!-- column -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">لیست نمونه محصولات</h4>

                        <!-- When there aren't anything for show -->
                        <!-- <p class="p-3 pt-4 bg-info radius text-white op-5"><i class="mdi mdi-information-outline ml-2"></i>اطلاعاتی برای نمایش وجود ندارد.</p> -->
                        <!-- When there aren't anything for show -->

                        <div class="table-responsive  ">
                            <table id="demo-foo-addrow" class="table" data-page-size="50">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>طرح گل</th>
                                    <th>برند</th>
                                    <th>کشور</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)

                                    <tr>
                                        <td><small>{{$product->id}}</small></td>
                                        <td><small>{{$product->designed}}</small></td>
                                        <td><small>{{$product->brand}}</small></td>
                                        <td><small>{{$product->country}}</small></td>
                                        <td>
                                            <form class="btn-fixer d-inline-block " action="">
                                                <a href={{route('productInfo' , $product->id)}} class="label
                                                   label-themecolor">مشاهده
                                                جزئیات</a>

                                            </form>

                                            <button type="button" onclick="deleteData({{ $product->id}})" class="label label-danger  " data-toggle="modal" data-target=".bs-example-modal-sm" >پاک کن</button>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{$products->links()}}
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

                                <form class="modal-body" id="deleteForm" method="get" action="">
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



                </div>
            </div>
        </div>
        <script type="text/javascript">
            function deleteData(id)
            {
                var id = id;
                var url = '{{ route("deleteProduct", ":id") }}';
                url = url.replace(':id', id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit()
            {
                $("#deleteForm").submit();
            }
        </script>

        <!-- Row - Tables  -->




        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
