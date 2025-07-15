<?php

namespace JeroenNoten\LaravelAdminLte\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

abstract class DataTableBuilder
{
    /**
     * DataTable HTML id attribute.
     *
     * @var string
     */
    protected string $tableId;

    /**
     * DataTable HTML class attribute.
     *
     * @var string
     */
    protected string $tableClass = 'table table-bordered table-hover text-nowrap';

    /**
     * Page length of the DataTable.
     *
     * @var int
     */
    protected int $pageLength = 25;

    /**
     * Initial orders of the DataTable.
     *
     * @var array<int, array<int, mixed>>
     */
    protected array $orders = [[0, 'desc']];

    /**
     * Whether the DataTable has an action column.
     *
     * @var bool
     */
    protected bool $hasAction = false;

    /**
     * Whether to enable smart search.
     *
     * @var bool
     */
    protected bool $smart = false;

    /**
     * Default options for the DataTable.
     *
     * @var array<string, mixed>
     */
    private array $defaultOptions = [
        'dom' => "<'row'<'col-sm-12'tr>>"."<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        'autoWidth' => false,
        'processing' => true,
        'searchDelay' => 300,
        'serverSide' => true,
    ];

    /**
     * Get the DataTable HTML id attribute.
     *
     * @return string
     */
    private function tableId(): string
    {
        return $this->tableId ?? Str::of(class_basename($this))->remove('DataTable')->prepend('dataTable');
    }

    /**
     * Get the ajax URL for DataTable query.
     *
     * @return string
     */
    protected function ajaxUrl(): string
    {
        return route(Request::route()->getName());
    }

    /**
     * Get the DataTable HTML builder with default options.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    private function htmlBuilder(): HtmlBuilder
    {
        return app(HtmlBuilder::class)
            ->setTableId($this->tableId())
            ->setTableAttribute('class', $this->tableClass)
            ->ajax(['type' => 'POST', 'url' => $this->ajaxUrl()])
            ->parameters($this->defaultOptions)
            ->pageLength($this->pageLength)
            ->orders($this->orders);
    }

    /**
     * Apply custom options to the DataTable HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    protected function prepareHtmlBuilder(): HtmlBuilder
    {
        return $this->htmlBuilder();
    }

    /**
     * Get the columns for the DataTable.
     *
     * @return array<int, \Yajra\DataTables\Html\Column>
     */
    abstract protected function columns(): array;

    /**
     * Share the DataTable to the view.
     *
     * @param  \Yajra\DataTables\Html\Builder  $dataTable
     * @return void
     */
    protected function shareToView(HtmlBuilder $dataTable): void
    {
        $dataTables = View::shared('dataTables', []);
        $dataTables[$this->tableId()] = $dataTable;
        View::share('dataTables', $dataTables);
    }

    /**
     * Render the DataTable.
     *
     * @return void
     */
    public function render(): void
    {
        if (empty($this->columns())) return;

        // loop through columns
        $columns = Arr::map($this->columns(), function (Column $column) {
            if ($column->get('render') === true) {
                $column->renderJs($this->tableId().'.'.Str::camel($column->get('data')));
            }

            return $column;
        });

        // build DataTable
        $dataTable = $this->prepareHtmlBuilder()->columns($columns);

        // add action column
        if ($this->hasAction) {
            $dataTable->addAction(['render' => '$.fn.dataTable.render.'.$this->tableId().'.action']);
        }

        // share to view
        $this->shareToView($dataTable);
        unset($dataTable);
    }

    /**
     * Prepare the Eloquent builder for DataTable query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract protected function prepareEloquentBuilder(): Builder;

    /**
     * Query the DataTable via ajax.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax(): JsonResponse
    {
        $dataTableEloquent = DataTables::eloquent($this->prepareEloquentBuilder());

        // set transformer if exists
        if (method_exists($this, 'transform')) {
            $dataTableEloquent->setTransformer(fn ($model) => $this->transform($model));
        }

        return $dataTableEloquent->smart($this->smart)->toJson();
    }
}
