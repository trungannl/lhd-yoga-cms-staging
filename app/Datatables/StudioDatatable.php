<?php
namespace App\Datatables;

use Carbon\Carbon;
use App\Models\Studio;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class StudioDataTable extends DataTable
{
    protected function getColumns()
    {
        $column = [
            [
                'data' => 'id',
                'title' => 'ID',
                'orderable'  => true,
            ],
            [
                'data' => 'name',
                'title' => 'Name',
                'orderable'  => true,
            ],
            [
                'data' => 'address',
                'title' => 'Address',
                'orderable'  => true,
            ],
            [
                'data' => 'status',
                'title' => 'Status',
                'orderable'  => true,
            ],
        ];

        return $column;
    }

    public function dataTable($query, Request $request)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->editColumn('status', function ($studio){
                return $this->getStatusColumn($studio);
            })
            ->addColumn('action',function ($studio){
                return $this->getActions($studio);
            })
            ->rawColumns(array_merge($columns, ['action']))
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')) {
                    $search = $request->get('search');
                    $query->where(function($query) use ($search) {
                        $query->where('id', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
                }

                if ($request->has('status') && is_numeric($request->get('status'))) {
                    $query->where('status', $request->get('status'));
                }

                if ($request->has('from') && $request->get('from')) {
                    $from = new Carbon($request->get('from'));
                    $query->where('created_at', '>=', $from->format('Y-m-d'));
                }

                if ($request->has('to') && $request->get('to')) {
                    $to = new Carbon($request->get('to'));
                    $query->where('created_at', '<=', $to->format('Y-m-d'));
                }
            });

        return $dataTable;
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax('', null, [
                'search' => '$("input[name=search]").val()',
                'status' => '$("select[name=status]").val()',
                'from' => '$("input[name=from]").val()',
                'to' => '$("input[name=to]").val()',
            ])
            ->addAction(['title' => 'Action','width' => '50px', 'printable' => false])
            ->addCheckbox([
                'width' => '13px'
            ], true)
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'paging' => true,
                    'searching' => false,
                    'info' => false,
                    'searchDelay' => 300,
                    'order' => [],
                    'buttons' => [
                        'pageLength',
                        [
                            'extend' => 'csvHtml5',
                            'title' => 'staff_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4]]
                        ],
                        [
                            'extend' => 'excelHtml5',
                            'title' => 'staff_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4]]
                        ],
                        [
                            'extend' => 'pdfHtml5',
                            'title' => 'staff_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4]]
                        ]
                    ],
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true),
                    "initComplete" => "function(settings){renderButtons( settings.sTableId);renderPerpages( settings.sTableId);renderCheckbox( settings.sTableId);}",
                ]
            ));
    }

    public function query(Studio $model)
    {
        return $model->newQuery()->select("studios.*");
    }

    /**
     * get status display column
     *
     * @param [type] $column
     * @param [type] $attributeName
     * @return void
     */
    private function getStatusColumn($column)
    {
        if (isset($column)) {
            return "<span class='badge {$column->getDisplayStatus()['color']}'>{$column->getDisplayStatus()['text']}</span>";
        }
    }

    /**
     * get action column display
     *
     * @param [type] $column
     * @return void
     */
    private function getActions($column)
    {
        if (isset($column)) {
            return view('studios.datatables_actions', ['studio' => $column]);
        }
    }
}