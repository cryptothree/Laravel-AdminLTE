@section('plugins.Datatables', true)

@pushOnce('css')
    <style>
        .datatable-filter {
            column-gap: 1rem;
        }

        .datatable-filter > .form-group > .input-group > .form-control {
            width: 240px !important;
        }
    </style>
@endPushOnce

@isset($filter)
    <div class="d-flex flex-wrap datatable-filter">

        {{ $filter }}

        <div class="btn-group mb-3">
            <x-adminlte-button type="button" theme="default" icon="fa-solid fa-magnifying-glass" data-action="search" />
            <x-adminlte-button type="button" theme="default" icon="fa-solid fa-eraser" data-action="reset" />
            <x-adminlte-button type="button" theme="default" icon="fa-solid fa-rotate" data-action="refresh" />
        </div>
    </div>
@endisset

<div class="table-responsive">
    {!! $dataTables[$id]->table() !!}
</div>

@push('js')
    {!! $dataTables[$id]->scripts() !!}
@endpush

@pushOnce('js')
    <script>
        $(function () {
            $('.datatable-filter').on('click', 'button', function () {
                const $filter = $(this).closest('.datatable-filter');
                const action = $(this).data('action');
                const selector = $filter.next('.table-responsive').find('table').attr('id');
                const dataTable = window.LaravelDataTables[selector];

                switch (action) {
                    case 'search':
                        $filter.find('input[data-column],select[data-column]').each(function () {
                            const smart = !!$(this).data('smart');
                            dataTable.column($(this).data('column')).search($(this).val(), { smart });
                        });
                        dataTable.draw();
                        break;
                    case 'reset':
                        $filter.find('input,select').val('').trigger('change');
                        dataTable.columns().search('').draw();
                        break;
                    case 'refresh':
                        dataTable.ajax.reload(null, false);
                        break;
                }
            });
        });
    </script>
@endPushOnce
