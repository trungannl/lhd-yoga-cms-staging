@extends('layouts.app', ['page' => __('settings')])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Roles</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body card_backgroud">
                        <div id="list_roles">
                            @foreach($roles as $role)
                            <div class="col-md-12" id="element_role_{{ $role->id }}">
                                <div class="form-group">
                                    <a href="javascript:void(0)" class="form-control border-0 color-gray card_backgroud role_select" data-id="{{ $role->id }}">
                                        {{ $role->name }}
                                    </a>
                                    <a href="javascript:void(0)" class="form_icon_remove" style="display: none" id="role_remove_{{ $role->id }}" data-toggle="modal" data-target="#modal-default">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-12" id="group_input" style="display: none;">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Type Role Title Here">
                                <a href="javascript:void(0)" class="form_icon_check"><i class="fas fa-check"></i></a>
                            </div>
                        </div>

                        <div class="col-md-12" id="group_add_new">
                            <div class="form-group">
                                <a href="javascript:void(0)" id="add_new"><i class="fas fa-plus-circle pr-1"></i> Add new role</a>
                                <input type="hidden" name="role_id" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card card_backgroud">
                    <div class="card-header border-0">
                        <h3 class="card-title">Access To</h3>
                    </div>
                    <div class="card-body">
                        <div class="row" style="padding-left: 5px;">
                            @foreach($permissions as $pemission)
                            <a href="javascript:void(0)" class="permission_border" data-id="{{ $pemission->id }}" id="permission_{{ $pemission->id }}">
                                <i class="nav-icon fas"><img src="/images/icon/{{ $pemission->name }}.svg" alt="icon_dashboard"></i>
                                <p>{{ $pemission->name }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-question-circle" aria-hidden="true"></i> Confirm</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this role?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="confirm_no">No</button>
                    <button type="button" class="btn btn-primary" id="confirm_yes">Yes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@push('scripts_lib')
    <script type="text/javascript">
        $(function () {
            $('#add_new').click(function (){
                $('#group_input').show();
                $('#group_add_new').hide();
            });

            loadFirstRole();

            $('.form_icon_check').click(function (){
                saveNewRole();
            });

            $("input[name='name']").on('keypress',function(e) {
                if(e.which == 13) {
                    saveNewRole();
                }
            });

            $('.role_select').click(function (){
                selectRole($(this), $(this).data("id"));
            });

            $('.permission_border').click(function (){
                var element = $(this);
                var id = $(this).data("id");
                var roleId = $("input[name='role_id']").val();

                if (element.hasClass('permission_select')) {
                    $.ajax({
                        url: "{{ route('roles.revoke-permission-to-role') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'permission': id,
                            'roleId': roleId
                        },
                        success: function (data, textStatus, xhr) {
                            if (xhr.status == 200) {
                                element.removeClass('permission_select');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
                else {
                    $.ajax({
                        url: "{{ route('roles.give-permission-to-role') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'permission': id,
                            'roleId': roleId
                        },
                        success: function (data, textStatus, xhr) {
                            if (xhr.status == 200) {
                                element.addClass('permission_select');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }

            });

            $('#confirm_yes').click(function (){
                var roleId = $("input[name='role_id']").val();
                removeRole(roleId);
            });
        });

        function loadFirstRole()
        {
            var role_fist = $('.role_select').first();
            role_fist.removeClass('card_backgroud');
            $('#role_remove_' + role_fist.data("id")).show();
            loadPermissionRole(role_fist.data("id"));
            $("input[name='role_id']").val(role_fist.data("id"));
        }

        function saveNewRole()
        {
            var name = $("input[name='name']").val();

            if (name) {
                $.ajax({
                    url: "{{ route('roles.store') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'name': name
                    },
                    success: function (data, textStatus, xhr) {
                        if (xhr.status == 200) {
                            var element = '<div class="col-md-12" id="element_role_'+ data.id +'"><div class="form-group">';
                            element += '<a href="javascript:void(0)" class="form-control border-0 color-gray card_backgroud role_select" onclick="selectRole($(this), '+ data.id +')" data-id="'+ data.id +'">' + data.name + '</a>';
                            element += '<a href="javascript:void(0)" class="form_icon_remove" style="display: none" id="role_remove_'+ data.id +'" data-toggle="modal" data-target="#modal-default"><i class="fas fa-trash"></i></a>';
                            element += '</div></div>';
                            $("#list_roles").append(element);

                            $('#group_input').hide();
                            $('#group_add_new').show();
                            $("input[name='name']").val('');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
        }

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

        function selectRole(elm, id)
        {
            $('.role_select').each(function() {
                $(this).addClass('card_backgroud');
                let id = $(this).data("id");
                $('#role_remove_' + id).hide();
            });

            $('.permission_border').each(function() {
                $(this).removeClass('permission_select');
            });

            elm.removeClass('card_backgroud');
            $('#role_remove_' + id).show();
            $("input[name='role_id']").val(id);

            loadPermissionRole(id);
        }

        function removeRole(id)
        {
            $.ajax({
                url: "/roles/" + id,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data, textStatus, xhr) {
                    if (xhr.status == 200) {
                        $('#element_role_' + id).remove();
                        $('#confirm_no').click();
                        loadFirstRole();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

    </script>
@endpush
