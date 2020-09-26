@if (count($errors)>0)

            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" style="margin: 10px 31px">
                    <strong>{{$error}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach

@endif

