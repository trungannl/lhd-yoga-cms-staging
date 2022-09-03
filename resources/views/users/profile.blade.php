@extends('layouts.app', ['page' => __('users')])

@push('css_lib')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        Back
                    </a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2 p-3">
                <div class="col-md-6">
                    <div class="row">
                        <div class="float-left">
                            <img class="profile-user-img img-fluid img-circle" src=" @if ($user->hasMedia('avatar')) {{ $user->getFirstMediaUrl('avatar') }}@else /images/avatar_default.png @endif" alt="staff avatar">
                        </div>
                        <div class="float-left pl-3">
                            <h1>{{ $user->name }}</h1>
                        </div>
                        <div class="float-left p-2">
                            @if ($user->active)
                            <span class='badge badge-success'>Active</span>
                            @else
                            <span class='badge badge-danger'>Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    @if ($user->active)
                        {!! Form::button('<i class="fas fa fa-lock"></i>', [
                           'type' => 'button',
                           'class' => 'btn btn-app btn_custom',
                           'data-toggle' => 'modal',
                           'data-target' => '#modal-active',
                           'onclick' => "activeItem($user->id, 'inactive')"
                           ]) !!}
                    @else
                        {!! Form::button('<i class="fas fa fa-unlock"></i>', [
                            'type' => 'button',
                            'class' => 'btn btn-app btn_custom',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal-active',
                            'onclick' => "activeItem($user->id, 'active')"
                            ]) !!}
                    @endif
{{--                    <a class="btn btn-app btn_custom" href="{{ route("users.edit", $user->id) }}">--}}
{{--                        <i class="fas fa-pencil-alt"></i>--}}
{{--                    </a>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card_backgroud">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>User Full Name</label>
                                                <div class="form-control border-0">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone number</label>
                                                <div class="form-control border-0">
                                                    {{ $user->phone }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <div class="form-control border-0">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-control border-0 text-center" style="border-radius: 8px; @if ($user->gender =='male') border: 1px solid #495057 !important; @else color: #d9d9d9; @endif">
                                                            Male
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-control border-0 text-center" style="border-radius: 8px; @if ($user->gender =='female') border: 1px solid #495057 !important; @else color: #d9d9d9; @endif">
                                                            Female
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Birthday</label>
                                                <div class="form-control border-0">
                                                    {{ ($user->birthday) ? date('d/m/Y', strtotime($user->birthday)) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Joined Date</label>
                                                <div class="form-control border-0">
                                                    {{ ($user->created_at) ? date('d/m/Y', strtotime($user->created_at)) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Last Logined</label>
                                                <div class="form-control border-0">
                                                    {{ ($user->last_login) ? date('d/m/Y ! h:i', strtotime($user->last_login)) : ''}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="modal fade" id="modal-active">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-question-circle" aria-hidden="true"></i> Confirm</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="message_active">Are you sure you want to active this user?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="id_active" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="confirm_no_active">No</button>
                    <button type="button" class="btn btn-primary" id="confirm_yes_active">Yes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_lib')
    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
    <script type="text/javascript">
        function activeItem(id, type)
        {
            $("input[name='id_active']").val(id);
            if (type == 'active') {
                $('#message_active').html('Are you sure you want to active this user?');
            }
            else {
                $('#message_active').html('Are you sure you want to inactive this user?');
            }
        }

        $(function (){
            $('#confirm_yes_active').click(function (){
                var id = $("input[name='id_active']").val();
                var hostname = window.location.origin;
                $.ajax({
                    url: hostname + "/users/active/" + id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (data, textStatus, xhr) {
                        if (xhr.status == 200) {
                            location.reload();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
@endpush
