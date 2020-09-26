@extends('admin.layout.master')

@section('headTitle')
    <title>صفحه کاربران</title>
@endsection

@section('pageTitle')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor pt-2">مشخصات کاربران</h3>
        </div>
        <div class="col-md-7 align-self-center pt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">پنل مدیریت</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)"> کاربران</a></li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    @if(Session::has('deleteUser'))
        <div class="alert alert-warning alert-dismissible fade show" style="margin: 10px 31px">
            <strong>{{session('deleteUser')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->

            <!-- Row - Tables  -->
            <div class="row">
                <!-- column -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">کاربران شما</h4>
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table" data-page-size="50">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام</th>
                                        <th>نام خانوادگی</th>
                                        <th>نام کاربری</th>
                                        <th>تاریخ ثبت نام</th>
                                        <th>وضعیت کاربر</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($usersWithInfo as $user)
                                    <tr>
                                        <td><small>{{$i}}</small></td>
                                        <td><small>@if(isset($user[0][0]->name)){{$user[0][0]->name}}@else <span>Undefined</span>@endif</small></td>
                                        <td><small>@if(isset($user[0][0]->family)){{$user[0][0]->family}}@else <span>Undefined</span>@endif</small></td>
                                        <td><small>{{$user[1]}}</small></td>
                                        <td><small>@if(isset($user[2])){{jdate($user[2])->format('%A, %d %B %y')}}@else <span>Undefined</span>@endif</small></td>
                                        <td><small>تایید نشده است</small></td>
                                        <td>
                                            <form class="btn-fixer" action="">
                                                <a href={{route('userInfo' , $user[3])}} class="label label-themecolor">مشاهده جزئیات</a>
                                                <button type="button" onclick="deleteData({{$user[3]}})" class="label label-danger" data-toggle="modal" data-target=".bs-example-modal-sm" >پاک کن</button>
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

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
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




</body>

        <script type="text/javascript">
            function deleteData(id)
            {
                var id = id;
                var url = '{{ route("deleteUser", ":id") }}';
                url = url.replace(':id', id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit()
            {
                $("#deleteForm").submit();
            }
        </script>

</html>
@endsection
