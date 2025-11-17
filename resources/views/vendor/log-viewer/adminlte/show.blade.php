@extends('log-viewer::adminlte._master')

@section('content')
    <div class="row">
        <div class="col-lg-2">
            {{-- Log Menu --}}
            <x-adminlte-card title="Levels" icon="fa-solid fa-flag fa-fw" theme="dark" theme-mode="outline" body-class="p-0">
                <div class="list-group list-group-flush log-menu">
                    @foreach($log->menu() as $levelKey => $item)
                        @if ($item['count'] === 0)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge empty">{{ $item['count'] }}</span>
                            </a>
                        @else
                            <a href="{{ $item['url'] }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center level-{{ $levelKey }}{{ $level === $levelKey ? ' active' : ''}}">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge badge-level-{{ $levelKey }}">{{ $item['count'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </x-adminlte-card>
        </div>

        <div class="col-lg-10">
            <x-adminlte-card title="Log [{{ $log->date }}]" theme="dark" theme-mode="outline">
                <x-slot name="toolsSlot">
                    <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-xs btn-success">
                        <i class="fa-solid fa-download"></i> @lang('Download')
                    </a>
                    <a href="#delete-log-modal" class="btn btn-xs btn-danger" data-toggle="modal">
                        <i class="fa-solid fa-trash"></i> @lang('Delete')
                    </a>
                </x-slot>

                <div class="table-responsive">
                    <table class="table table-bordered table-condensed text-nowrap mb-0">
                        <tbody>
                            <tr>
                                <td>@lang('File path') :</td>
                                <td colspan="7">{{ $log->getPath() }}</td>
                            </tr>
                            <tr>
                                <td>@lang('Log entries') :</td>
                                <td class="text-bold">{{ $entries->total() }}</td>
                                <td>@lang('Size') :</td>
                                <td class="text-bold">{{ $log->size() }}</td>
                                <td>@lang('Created at') :</td>
                                <td class="text-bold">{{ $log->createdAt() }}</td>
                                <td>@lang('Updated at') :</td>
                                <td class="text-bold">{{ $log->updatedAt() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <x-slot name="footerSlot">
                    <form action="{{ route('log-viewer::logs.search', [$log->date, $level]) }}" method="GET">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input id="query" name="query" class="form-control" value="{{ $query }}" placeholder="@lang('Type here to search')">
                                <div class="input-group-append">
                                    @unless (is_null($query))
                                        <a href="{{ route('log-viewer::logs.show', [$log->date]) }}" class="btn btn-secondary">
                                            (@lang(':count results', ['count' => $entries->count()])) <i class="fa-solid fa-xmark fa-fw"></i>
                                        </a>
                                    @endunless
                                    <button id="search-btn" class="btn btn-primary">
                                        <span class="fa-solid fa-magnifying-glass fa-fw"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </x-slot>
            </x-adminlte-card>

            {{-- Log Entries --}}
            <div class="card">
                @if ($entries->hasPages())
                    <div class="card-header">
                        <span class="badge badge-light">
                            {{ __('Page :current of :last', ['current' => $entries->currentPage(), 'last' => $entries->lastPage()]) }}
                        </span>
                        <div class="card-tools">
                            {!! $entries->appends(compact('query'))->render() !!}
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="entries" class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('ENV')</th>
                                    <th style="width: 120px;">@lang('Level')</th>
                                    <th style="width: 65px;">@lang('Time')</th>
                                    <th>@lang('Header')</th>
                                    <th class="text-right">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $key => $entry)
                                    <tr>
                                        <td>
                                            <span class="badge badge-env">{{ $entry->env }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-level-{{ $entry->level }}">
                                                {!! $entry->level() !!}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $entry->datetime->format('H:i:s') }}
                                            </span>
                                        </td>
                                        <td class="break-word">
                                            {{ $entry->header }}
                                        </td>
                                        <td class="text-right">
                                            @if ($entry->hasStack())
                                                <a class="btn btn-xs btn-default text-nowrap m-1" role="button" data-toggle="collapse"
                                                   href="#log-stack-{{ $key }}" aria-expanded="false" aria-controls="log-stack-{{ $key }}">
                                                    <i class="fa-solid fa-toggle-on"></i> @lang('Stack')
                                                </a>
                                            @endif

                                            @if ($entry->hasContext())
                                                <a class="btn btn-xs btn-default text-nowrap m-1" role="button" data-toggle="collapse"
                                                   href="#log-context-{{ $key }}" aria-expanded="false" aria-controls="log-context-{{ $key }}">
                                                    <i class="fa-solid fa-toggle-on"></i> @lang('Context')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($entry->hasStack() || $entry->hasContext())
                                        <tr>
                                            <td colspan="5" class="stack p-0">
                                                @if ($entry->hasStack())
                                                    <div class="stack-content collapse py-3 px-4" id="log-stack-{{ $key }}">
                                                        {!! $entry->stack() !!}
                                                    </div>
                                                @endif

                                                @if ($entry->hasContext())
                                                    <div class="stack-content collapse py-3 px-4" id="log-context-{{ $key }}">
                                                        <pre class="p-0 mb-0">{{ $entry->context() }}</pre>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="text-muted font-italic">@lang('The list of logs is empty!')</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($entries->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-inline-block float-right">
                            {!! $entries->appends(compact('query'))->render() !!}
                        </div>
                    </div>
                @endif
            </div>
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
                <input type="hidden" name="date" value="{{ $log->date }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Delete log file')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to delete this log file: :date ?', ['date' => $log->date])</p>
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
                            location.replace("{{ route('log-viewer::logs.list') }}");
                        } else {
                            alert('OOPS ! This is a lack of coffee exception !');
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

            @unless (empty(log_styler()->toHighlight()))
            @php
                $htmlHighlight = version_compare(PHP_VERSION, '7.4.0') >= 0
                    ? join('|', log_styler()->toHighlight())
                    : join(log_styler()->toHighlight(), '|');
            @endphp

            $('.stack-content').each(function () {
                const $this = $(this);
                const html = $this.html().trim()
                    .replace(/({!! $htmlHighlight !!})/gm, '<strong>$1</strong>')
                    .replace('{main} {', "{main}\n\n{");

                $this.html(html);
            });
            @endunless
        });
    </script>
@endsection
