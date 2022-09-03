<?php


namespace App\Datatables;

use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class UserDatatable extends DataTable
{
    protected function getColumns()
    {
        $column = [
            [
                'data' => 'name',
                'title' => 'Name',
                'orderable'  => true,
            ],
            [
                'data' => 'gender',
                'title' => 'Gender',
                'orderable'  => true,
            ],
            [
                'data' => 'joined_date',
                'title' => 'Joined Date',
                'orderable'  => true,
            ],
            [
                'data' => 'active',
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
            ->editColumn('name', function ($user){
                return getNameAvatarColumn($user, 'name');
            })
            ->editColumn('joined_date', function ($user){
                return getDateColumn($user, 'joined_date');
            })
            ->editColumn('active', function ($user){
                return getActiveColumn($user, 'active');
            })
            ->addColumn('action', 'users.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']))
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')) {
                    $search = $request->get('search');
                    $query->where(function($query) use ($search) {
                        $query->Where('phone', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
                }

                if ($request->has('active') && is_numeric($request->get('active'))) {
                    $query->where('active', $request->get('active'));
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
                'active' => '$("select[name=status]").val()',
                'from' => '$("input[name=from]").val()',
                'to' => '$("input[name=to]").val()'
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
                            'title' => 'user_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4, 5, 6]]
                        ],
                        [
                            'extend' => 'excelHtml5',
                            'title' => 'user_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4, 5, 6]]
                        ],
                        [
                            'extend' => 'pdfHtml5',
                            'title' => 'user_export_' . time(),
                            'exportOptions' => ['columns' => [1, 2, 3, 4, 5, 6]]
                        ]
                    ],
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true),
                    "initComplete" => "function(settings){renderButtons( settings.sTableId);renderPerpages( settings.sTableId);renderCheckbox( settings.sTableId);}",
                ]
            ));
    }

    public function query(User $model)
    {
        return $model->newQuery()->select("users.*");
    }
}
