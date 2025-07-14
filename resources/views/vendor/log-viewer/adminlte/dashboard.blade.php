@extends('log-viewer::adminlte._master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Statistics" theme="primary" theme-mode="outline">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <canvas id="stats-doughnut-chart" height="300" class="mb-3"></canvas>
                    </div>

                    <div class="col-md-6 col-lg-9">
                        <div class="row">
                            @foreach($percents as $level => $item)
                                <div class="col-sm-6 col-md-12 col-lg-4 mb-3">
                                    <div class="info-box level-{{ $level }} {{ $item['count'] === 0 ? 'empty' : '' }}">
                                        <span class="info-box-icon">
                                            {!! log_styler()->icon($level) !!}
                                        </span>

                                        <div class="info-box-content">
                                            <span class="info-box-text text-bold">{{ $item['name'] }}</span>
                                            <span class="info-box-number">{{ $item['count'] }} @lang('entries') - {!! $item['percent'] !!} %</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: {{ $item['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('plugins.Chartjs', true)

@section('scripts')
    <script>
        $(function () {
            new Chart(document.getElementById('stats-doughnut-chart'), {
                type: 'doughnut',
                data: {!! $chartData !!},
                options: {
                    legend: {
                        position: 'bottom',
                    },
                },
            });
        });
    </script>
@endsection
