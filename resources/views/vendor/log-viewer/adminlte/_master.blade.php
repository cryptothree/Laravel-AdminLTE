@extends('adminlte::page')

@push('css')
    <style>
        /*
         * Log Entry
         */

        .stack-content {
            color: #AE0E0E;
            font-family: consolas, Menlo, Courier, monospace;
            white-space: pre-line;
            word-break: break-word;
            font-size: .8rem;
            background-color: #F6F6F6;
            border-bottom: 1px solid #DEE2E6;
        }

        .stack-content pre {
            white-space: pre-wrap;
            word-break: break-all;
        }

        .break-word {
            word-break: break-word;
        }

        /*
        * Colors: Badge & Infobox
        */

        .badge {
            padding: 0.25rem 0.4rem !important;
        }

        .badge.badge-env,
        .badge.badge-level-all,
        .badge.badge-level-emergency,
        .badge.badge-level-alert,
        .badge.badge-level-critical,
        .badge.badge-level-error,
        .badge.badge-level-warning,
        .badge.badge-level-notice,
        .badge.badge-level-info,
        .badge.badge-level-debug,
        .badge.empty {
            color: #FFF;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }

        .badge.badge-level-all,
        .info-box.level-all {
            background-color: {{ log_styler()->color('all') }};
        }

        .badge.badge-level-emergency,
        .info-box.level-emergency {
            background-color: {{ log_styler()->color('emergency') }};
        }

        .badge.badge-level-alert,
        .info-box.level-alert {
            background-color: {{ log_styler()->color('alert') }};
        }

        .badge.badge-level-critical,
        .info-box.level-critical {
            background-color: {{ log_styler()->color('critical') }};
        }

        .badge.badge-level-error,
        .info-box.level-error {
            background-color: {{ log_styler()->color('error') }};
        }

        .badge.badge-level-warning,
        .info-box.level-warning {
            background-color: {{ log_styler()->color('warning') }};
        }

        .badge.badge-level-notice,
        .info-box.level-notice {
            background-color: {{ log_styler()->color('notice') }};
        }

        .badge.badge-level-info,
        .info-box.level-info {
            background-color: {{ log_styler()->color('info') }};
        }

        .badge.badge-level-debug,
        .info-box.level-debug {
            background-color: {{ log_styler()->color('debug') }};
        }

        .badge.empty,
        .info-box.empty {
            background-color: {{ log_styler()->color('empty') }};
        }

        .badge.badge-env {
            background-color: #6A1B9A;
        }

        .info-box .info-box-icon, .info-box .info-box-content .info-box-text, .info-box .info-box-content .info-box-number {
            color: #FFF;
        }

        ul.pagination {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('footer')
    LogViewer - <span class="badge badge-primary">version {{ log_viewer()->version() }}</span>
@endsection

@push('js')
    @yield('modals')
    @yield('scripts')
@endpush
