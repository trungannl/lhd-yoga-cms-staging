@extends('layouts.app', ['page' => (!$isProfile) ? __('staffs') : ''])

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
                    @if (!$isProfile)
                    <a href="{{ route('staffs.index') }}">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        Back
                    </a>
                    @endif
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
                            <img class="profile-user-img img-fluid img-circle" src=" @if ($staff->hasMedia('avatar')) {{ $staff->getFirstMediaUrl('avatar') }}@else /images/avatar_default.png @endif" alt="staff avatar">
                        </div>
                        <div class="float-left pl-3">
                            <h1>{{ $staff->name }}</h1>
                        </div>
                        <div class="float-left p-2">
                            @if ($staff->active)
                            <span class='badge badge-success'>Active</span>
                            @else
                            <span class='badge badge-danger'>Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    @if ($isProfile)
                        <div class="float-sm-right">
                            <a href="{{ route('profile.change_password') }}" class="btn btn-block btn-outline-success">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                Change Password
                            </a>
                        </div>
                    @else
                        {!! Form::open(['route' => ['staffs.active', $staff->id], 'method' => 'post', 'style' => 'display: inline-block;']) !!}
                        @if ($staff->active)
                            {!! Form::button('<i class="fas fa fa-lock"></i>', [
                               'type' => 'submit',
                               'class' => 'btn btn-app btn_custom',
                               'onclick' => "return confirm('Are you sure?')"
                               ]) !!}
                        @else
                            {!! Form::button('<i class="fas fa fa-unlock"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-app btn_custom',
                                'onclick' => "return confirm('Are you sure?')"
                                ]) !!}
                        @endif
                        {!! Form::close() !!}
                        <a class="btn btn-app btn_custom" href="{{ route("staffs.edit", $staff->id) }}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card_backgroud">
                        <div class="card-header border-0">
                            <h3 class="card-title">Staff Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Display Name</label>
                                        <div class="form-control border-0">
                                            {{ $staff->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone number</label>
                                        <div class="form-control border-0">
                                            {{ $staff->phone }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="form-control border-0">
                                            {{ $staff->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Register Date</label>
                                        <div class="form-control border-0">
                                            {{ ($staff->created_at) ? date('d/m/Y', strtotime($staff->created_at)) : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Joined Date</label>
                                        <div class="form-control border-0">
                                            {{ ($staff->created_at) ? date('d/m/Y', strtotime($staff->created_at)) : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Logined</label>
                                        <div class="form-control border-0">
                                            {{ ($staff->last_login) ? date('d/m/Y', strtotime($staff->last_login)) : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card_backgroud">
                        <div class="card-header border-0">
                            <h3 class="card-title">Staff Role & Access Rights</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Staff Role</label>
                                        <div class="form-control border-0">
                                            {{ ($staff->role) ? $staff->role->name : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Access To</label>
                                </div>
                                <div class="card-body" style="min-height: 228px;">
                                    <div class="row" style="padding-left: 5px;">
                                        @foreach($permissions as $pemission)
                                            <div class="permission_border" data-id="{{ $pemission->id }}" id="permission_{{ $pemission->id }}">
                                                <i class="nav-icon fas"><img src="/images/icon/{{ $pemission->name }}.svg" alt="icon_dashboard"></i>
                                                <p>{{ $pemission->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

@push('scripts_lib')
    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
    <script type="text/javascript">
        $(function () {
            @if ($staff->role_id)
            loadPermissionRole({{ $staff->role_id }})
            @endif

            @if(Session::has('success'))
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}'
            })
            @endif

        });

        function loadPermissionRole(id)
        {
            $.ajax({
                url: "/roles/" + id,
                type: 'GET',
                data: {},
                success: function (data, textStatus, xhr) {
                    if (xhr.status == 200) {
                        data.forEach(function(item) {
                            $('#permission_' + item.id).addClass('permission_select');
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    </script>
@endpush
