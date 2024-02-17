<?php

namespace App\DataTables;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SurveyDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($survey) {
                return view('admin.surveys._action', compact('survey'))->render();
            })
            ->editColumn('company_name', function ($model) {
                $image = Storage::url($model->company->logo);
                $name = $model->company->name;

                return "<div class='flex'><img src=$image alt='' width='40'/> <span>$name</span></div>";
            })
            ->editColumn('remaining_users', function ($model) {
                $total_answers = $model->answers()->distinct('user_id')->count();
                return $model->max_participants - $total_answers;
            })
            ->editColumn('status', function ($model) {
                return $model->is_closed ? "<span style='color: red'>Closed</span>" : "<span style='color: green'>Open</span>";
            })
            ->rawColumns(['status', 'action', 'company_name', 'remaining_users', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Survey $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('survey-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('title')->title('Survey Title'),
            Column::make('company_name')->title('Company Name'),
            Column::make('remaining_users')->title('Remaining users'),
            Column::make('status')->title('Status'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Survey_' . date('YmdHis');
    }
}
