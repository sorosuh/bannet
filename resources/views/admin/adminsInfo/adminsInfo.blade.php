@extends('admin.layout.master')


@section('headTitle')
    <title>صفحه ادمین</title>
@endsection
@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">مشخصات ادمین</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">مشخصات ادمین</a></li>
            </ol>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        @include('partials.errors')
        @include('partials.modal')
        @if(Session::has('addAdmin'))
            <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('addAdmin')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(Session::has('editAdmin'))
            <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('editAdmin')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(Session::has('deleteAdmin'))
            <div class="alert alert-success alert-dismissible fade show" style="margin: 10px 31px">
                <strong>{{session('deleteAdmin')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    @endif


    <!-- Row - Tables  -->
        <div class="row">
            <!-- column -->
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ویرایش | ثبت مدیر</h4>
                        @if(isset($admin))
                            <form class="form-material m-t-40 row" method="POST"
                                  action="{{route('editAdmin' , $admin->id)}}">
                                @else
                                    <form class="form-material m-t-40 row" method="GET" action="{{route('addAdmin')}}">
                                        @endif
                                        <div class="form-group col-12 m-t-2">
                                            <label class="mb-0" for="membership-title">نام کاربری</label>
                                            <input name="username" id="membership-title" type="text"
                                                   class="form-control form-control-line"
                                                   tabindex="1"
                                                   value="@if(isset($admin->username)) {{$admin->username}} @endif"
                                            >
                                        </div>

                                        <div class="form-group col-12 m-t-2">
                                            <label class="mb-0" for="example-price">گذرواژه</label>
                                            <input name="password" id="membership-price" type="password"
                                                   class="form-control form-control-line"
                                                   tabindex="2">
                                        </div>

                                        <div class="form-group col-12 m-t-2">
                                            <label class="mb-0" for="membership-dmg-one">شماره همراه</label>
                                            <input name="phone" id="membership-dmg-one" type="text"
                                                   class="form-control form-control-line"
                                                   tabindex="3"
                                                   value="@if(isset($admin->phone)) {{$admin->phone}} @endif"
                                            >
                                            <small class="help"> شماره همراه بدون صفر اول وارد شود</small>
                                        </div>

                                        <div class="col-12 m-t-20 text-left">
                                            <button type="submit"
                                                    class="btn btn-info waves-effect waves-light m-r-10 pb-0"
                                                    tabindex="4">ذخیره
                                            </button>
                                        </div>
                                    </form>
                    </div>
                </div>
            </div>
            <!-- column -->
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">مدیران</h4>


                        <!-- Row - Tables  -->
                        <div class="row">
                            <!-- column -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="demo-foo-addrow" class="table" data-page-size="50">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>نام کاربری</th>
                                                    <th>شماره همراه</th>
                                                    <th>ویرایش</th>
                                                    <th>حذف</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($i=1)
                                                @foreach($admins as $admin)
                                                    <tr>
                                                        <td><small>{{$i}}</small></td>
                                                        <td><small>{{$admin->username}}</small></td>
                                                        <td><small>{{$admin->phone}}</small></td>
                                                        <th>
                                                            <form method="get"
                                                                  action="{{route('editPage' , $admin->id)}}">
                                                                <button name="edit" type="submit"
                                                                        class="label label-warning">ویرایش
                                                                </button>
                                                            </form>

                                                        </th>
                                                        <th>

                                                            <button type="button" onclick="deleteData({{$admin->id}})" class="label label-danger" data-toggle="modal" data-target=".bs-example-modal-sm" >پاک کن</button>


                                                        </th>
                                                    </tr>
                                                    @php($i++)
                                                @endforeach
                                                </tbody>
                                            </table>
                                            {{$admins->links()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Row - Tables  -->



                        <!-- sample modal content -->

                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="deleted-modal" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title mt-2" id="deleted-modal">حذف رکورد</h4>
                                        <button type="button" class="close-modal float-right" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>

                                 <form class="modal-body" method="get" action="" id="deleteForm">
                                     <p class="text-right">آیا از حذف رکورد مطمئن هستید؟</p>
                                     <div class="text-left mt-3">
                                         <button type="submit" class="btn btn-sm btn-danger pb-0">حذفش کن</button>
                                         <button type="button" class="btn btn-sm btn-info pb-0" data-toggle="modal" data-target=".bs-example-modal-sm">بیخیال</button>
                                     </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->


                        <!-- When there aren't anything for show -->
                        @if(count($admins) == 0)
                            <p class="p-3 pt-4 bg-info radius text-white op-5"><i
                                    class="mdi mdi-information-outline ml-2"></i>اطلاعاتی برای نمایش وجود ندارد.</p>
                    @endif
                    <!-- When there aren't anything for show -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Row - Tables  -->


        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
    <script type="text/javascript">
        function deleteData(id)
        {
            var id = id;
            var url = '{{ route("deleteAdmin", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }
    </script>


@endsection
