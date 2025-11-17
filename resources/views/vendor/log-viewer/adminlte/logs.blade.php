@extends('log-viewer::adminlte._master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Logs" theme="primary" theme-mode="outline">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-nowrap">
                        <thead>
                            <tr>
                                @foreach($headers as $key => $header)
                                    <th scope="col" class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                        @if ($key == 'date')
                                            <span style="color: {{ log_styler()->color('info') }}">{{ $header }}</span>
                                        @else
                                            <span style="color: {{ log_styler()->color($key) }}">
                                                {{ log_styler()->icon($key) }} {{ $header }}
                                            </span>
                                        @endif
                                    </th>
                                @endforeach
                                <th scope="col" class="text-right">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $date => $row)
                                <tr>
                                    @foreach($row as $key => $value)
                                        <td class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                            @if ($key == 'date')
                                                <span class="badge badge-primary">{{ $value }}</span>
                                            @elseif ($value == 0)
                                                <span class="badge empty">{{ $value }}</span>
                                            @else
                                                <a href="{{ route('log-viewer::logs.filter', [$date, $key]) }}">
                                                    <span class="badge badge-level-{{ $key }}">{{ $value }}</span>
                                                </a>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-right">
                                        <a href="{{ route('log-viewer::logs.show', [$date]) }}" class="btn btn-xs btn-info">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </a>
                                        <a href="{{ route('log-viewer::logs.download', [$date]) }}" class="btn btn-xs btn-success">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        <a href="#delete-log-modal" class="btn btn-xs btn-danger" data-log-date="{{ $date }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <span class="text-muted font-italic">@lang('The list of logs is empty!')</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <x-slot name="footerSlot">
                    <div class="float-right">
                        {{ $rows->render() }}
                    </div>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('modals')
    {{-- DELETE MODAL --}}
    <div id="delete-log-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="date" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Delete log file')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary mr-auto" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-sm btn-danger" data-loading-text="@lang('Loading')&hellip;">@lang('Delete')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            const deleteLogModal = $('div#delete-log-modal'),
                deleteLogForm = $('form#delete-log-form'),
                submitBtn = deleteLogForm.find('button[type=submit]');

            $("a[href='#delete-log-modal']").on('click', function (event) {
                event.preventDefault();
                const date = $(this).data('log-date'),
                    message = "{{ __('Are you sure you want to delete this log file: :date ?') }}";

                deleteLogForm.find('input[name=date]').val(date);
                deleteLogModal.find('.modal-body p').html(message.replace(':date', date));

                deleteLogModal.modal('show');
            });

            deleteLogForm.on('submit', function (event) {
                event.preventDefault();
                submitBtn.button('loading');

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        submitBtn.button('reset');
                        if (data.result === 'success') {
                            deleteLogModal.modal('hide');
                            location.reload();
                        } else {
                            alert('AJAX ERROR ! Check the console !');
                            console.error(data);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        alert('AJAX ERROR ! Check the console !');
                        console.error(errorThrown);
                        submitBtn.button('reset');
                    },
                });

                return false;
            });

            deleteLogModal.on('hidden.bs.modal', function () {
                deleteLogForm.find('input[name=date]').val('');
                deleteLogModal.find('.modal-body p').html('');
            });
        });
    </script>
@endsection
