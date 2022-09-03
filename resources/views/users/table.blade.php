@push('css_lib')
    @include('layouts.datatables_css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush

<div class="card">
    <div class="card-body">
        <form method="POST" id="search-form" role="form">
            <div class="row">
                <div class="col-6 input-icons">
                    <i class="fa fa-search icon"></i>
                    <input type="text" class="form-control" placeholder="Search by ID / Phone / Name" name="search">
                </div>
                <div class="col-3">
                    <select class="form-control" name="role">
                        <option value="" disabled selected>Account Type</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-3 input-icons">
                    <i class="fa fa-calendar icon"></i>
                    <input class="form-control" autocomplete="off" placeholder="From" name="from" type="text" id="from_date">
                </div>
                <div class="col-3 input-icons">
                    <i class="fa fa-calendar icon"></i>
                    <input class="form-control" autocomplete="off" placeholder="To" name="to" type="text" id="to_date">
                </div>
                <div class="col-3">
                    <select class="form-control" name="status">
                        <option value="" disabled selected>Status</option>
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-3">
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-block btn-outline-secondary">SEARCH</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-block btn-outline-secondary" id="reset_bt">RESET</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-block btn-outline-secondary" id="export_bt" data-toggle="dropdown">EXPORT</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" id="exportCsvDatatable" href="#"> <i class="fa fa-file-excel mr-2"></i>CSV</a>
                                <a class="dropdown-item" id="exportExcelDatatable" href="#"> <i class="fa fa-file-excel mr-2"></i>Excel</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" id="exportPdfDatatable" href="#"> <i class="fa fa-file-pdf mr-2"></i>PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="card">
    <div class="card-body card-body table-responsive p-0">

        {!! $dataTable->table(['width' => '100%', 'style' => 'margin-bottom: 20px !important']) !!}

        <div class="info_data">
            <span id="selectd_collect">0</span> selected / <span id="total_collect"></span> total
            <select id="per_page" class="select_per_page">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>


        <div class="clearfix"></div>
    </div>
</div>

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
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <input type="hidden" name="id_remove" value="0">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="confirm_no">No</button>
                <button type="button" class="btn btn-primary" id="confirm_yes">Yes</button>
            </div>
        </div>
    </div>
</div>

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


@push('scripts_lib')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script type="text/javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>

    <script type="text/javascript">
        $(function () {

            $("#from_date").datepicker();
            $("#to_date").datepicker();

            var table = $("#dataTableBuilder").DataTable();
            $('#search-form').on('submit', function(e) {
                table.draw();
                e.preventDefault();
            });

            $('#reset_bt').on('click', function(e) {
                $(this).closest('form').find("input[type=text], select").val("");
                table.draw();
                e.preventDefault();
            });

            $('#confirm_yes').click(function (){
                var id = $("input[name='id_remove']").val();
                var hostname = window.location.origin;
                $.ajax({
                    url: hostname + "/users/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (data, textStatus, xhr) {
                        if (xhr.status == 200) {
                            $('#confirm_no').click();
                            $('#reset_bt').click();
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'User removed successfully.'
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

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
                            $('#confirm_no_active').click();
                            $('#reset_bt').click();
                            var title = 'User inactive successfully.';
                            if (data.active == 1) {
                                title = 'User active successfully.';
                            }
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: title
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

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

        function removeItem(id)
        {
            $("input[name='id_remove']").val(id);
        }

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

    </script>

@endpush
