<?php

namespace App\DataTables;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WarehouseDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = [];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
            if(\Laratrust::hasPermission('warehouse show') || \Laratrust::hasPermission('warehouse edit') || \Laratrust::hasPermission('warehouse delete'))
            {
                $dataTable->addColumn('action', function (Warehouse $warehouse) {
                    return view('warehouses.action', compact('warehouse'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Warehouse $model): QueryBuilder
    {
        return $model->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('warehouse-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->initComplete('function() {
                                var table = this;
                                
                                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                                searchInput.removeClass(\'form-control form-control-sm\');
                                searchInput.addClass(\'dataTable-input\');
                                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
                            }');

        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary me-1 dropdown-toggle',
            'text' => '<i class="ti ti-download"></i> ' . __('Export'),
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger me-1',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
                            <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
                            <'dataTable-container'<'col-sm-12'tr>>
                            <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                                    var tooltipTriggerList = [].slice.call(
                                        document.querySelectorAll("[data-bs-toggle=tooltip]")
                                      );
                                      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                        return new bootstrap.Tooltip(tooltipTriggerEl);
                                      });
                                      var popoverTriggerList = [].slice.call(
                                        document.querySelectorAll("[data-bs-toggle=popover]")
                                      );
                                      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                                        return new bootstrap.Popover(popoverTriggerEl);
                                      });
                                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                                      var toastList = toastElList.map(function (toastEl) {
                                        return new bootstrap.Toast(toastEl);
                                      });
                                }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('address')->title(__('Address')),
            Column::make('city')->title(__('City')),
            Column::make('city_zip')->title(__('Zip Code'))
        ];
        if(\Laratrust::hasPermission('warehouse show') || \Laratrust::hasPermission('warehouse edit') || \Laratrust::hasPermission('warehouse delete'))
        {
            $action = [
                Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            ];
            $column = array_merge($column,$action);
        }
        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Warehouse_' . date('YmdHis');
    }
}