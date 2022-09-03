
<!-- DataTables -->
{{--<script type="text/javascript" src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{asset('plugins/datatables/dataTables.bootstrap4.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{asset('plugins/datatables/buttons/dataTables.buttons.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{asset('plugins/datatables/buttons.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{asset('plugins/datatables/buttons/buttons.colVis.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('plugins/datatables/buttons.server-side.js') }}"></script>--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/colreorder/1.5.0/js/dataTables.colReorder.js"></script>--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.js"></script>--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/rowgroup/1.0.3/js/dataTables.rowGroup.js"></script>--}}
{{--<script type="text/javascript" src="//cdn.datatables.net/select/1.3.1/js/dataTables.select.js"></script>--}}


<script type="text/javascript" src="//code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

    $(document).ready(function() { $.fn.dataTableExt.sErrMode = 'none'; });

    function renderButtons(tableId) {

        var dtable = $("#" + tableId).DataTable();
        $('a#exportCsvDatatable').on('click', function () {
            dtable.button('1').trigger();
        });
        $('a#exportExcelDatatable').on('click', function () {
            dtable.button('2').trigger();
        });

        $('a#exportPdfDatatable').on('click', function () {
            dtable.button('3').trigger();
        });
    }

    function renderPerpages(tableId) {
        var dtable = $("#" + tableId).DataTable();
        $('#per_page').change(function (){
            var value = $(this).val();
            switch (value) {
                case "10":
                    dtable.button('0-0').trigger();
                    break;
                case "25":
                    dtable.button('0-1').trigger();
                    break;
                case "50":
                    dtable.button('0-2').trigger();
                    break;
                case "100":
                    dtable.button('0-3').trigger();
                    break;
            }
        });
    }

    function renderCheckbox(tableId) {
        var dtable = $("#" + tableId).DataTable();
        if ($('#total_collect')) {
            $('#total_collect').html(dtable.rows().count());
        }

        // Handle click on "Select all" control
        $('#dataTablesCheckbox').on('click', function(){
            if ($('#dataTablesCheckbox').is(':checked')) {
                dtable.rows().select();
            }
            else {
                dtable.rows().deselect();
            }
            // Get all rows with search applied
            var rows = dtable.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);

            if ($('#selectd_collect')) {
                $('#selectd_collect').html(dtable.rows({ selected: true }).count());
            }

        });

        // Handle click on checkbox to set state of "Select all" control
        $('#'+ tableId +' tbody').on('change', 'input[type="checkbox"]', function(){
            // If checkbox is not checked
            if(!this.checked){
                $('#dataTablesCheckbox').prop('checked', false);
                dtable.rows($(this).parent().parent()).deselect();
            }
            else {
                dtable.rows($(this).parent().parent()).select();
                $('#dataTablesCheckbox').prop('checked', true);
                dtable.$('input[type="checkbox"]').each(function(){
                    if(!this.checked){
                        $('#dataTablesCheckbox').prop('checked', false);
                    }
                });
            }

            if ($('#selectd_collect')) {
                $('#selectd_collect').html(dtable.rows({ selected: true }).count());
            }
        });


    }

</script>
