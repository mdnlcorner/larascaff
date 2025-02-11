<?php

namespace Mulaidarinull\Larascaff\Datatable;

use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class HtmlBuilder extends Builder
{
    /**
     * Set column with actions
     */
    public function columnsWithActions(array $columns)
    {
        $this->columns([
            Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false),
            Column::make('id')->searchable(false)->orderable(true)->hidden(),
            ...$columns,
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ]);
    }
}
