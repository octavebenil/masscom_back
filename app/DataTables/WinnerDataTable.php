<?php

namespace App\DataTables;

use App\Models\Company;
use App\Models\Winner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WinnerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($winner) {
                return view('admin.surveys._winner_action', compact('winner'))->render();
            })
            ->editColumn('photo', function ($model) {
                $image = Storage::url($model->photo);
                $name = $model->nom;

                return "<div class='flex'><img src='$image' alt='$name' width='40'/></div>";
            })
            ->rawColumns(['status', 'action', 'nom'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Winner $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('winner-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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
                  ->printable(true)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('nom')->title('Nom'),
            Column::make('prenoms')->title('Prénoms'),
            Column::make('adresse')->title('Adresse'),
            Column::make('phone')->title('Téléphone'),
            Column::make('photo')->title('Photo'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Gagnant_' . date('YmdHis');
    }
}
