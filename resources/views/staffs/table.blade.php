@push('css_lib')
    @include('layouts.datatables_css')
@endpush

<div class="card">
    <div class="card-body">
        <form method="POST" id="search-form" role="form">
            <div class="row">
                <div class="col-6 input-icons">
                    <i class="fa fa-search icon"></i>
                    <input type="text" class="form-control" placeholder="Search by ID / Name / Email" name="search">
                </div>
                <div class="col-3">
                    <select class="form-control" name="role">
                        <option value="" disabled selected>Role</option>
                        <option value="">All</option>
                        @foreach($roles as $key=>$value) :
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
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




@push('scripts_lib')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

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

        });
    </script>

@endpush
