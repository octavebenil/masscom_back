@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <style>

    </style>
    <div class="row">
        <div class="col-xl-4">
            <div class="card bg-primary bg-soft">
                <div>
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Welcome Back!</h5>
                                <p class="mb-1">Masscom Admin Panel</p>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        {{--                                        <img src="{{ Vite::asset('resources/images/avatar.jpg') }}" alt=""--}}
                                        {{--                                             class="avatar-md rounded-circle img-thumbnail">--}}
                                    </div>
                                    <div class="flex-grow-1 align-self-center">
                                        <div class="text-muted">
                                            {{--                                            <h5 class="mb-1">Admin</h5>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="{{ Vite::asset('resources/images/profile-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div id="surveyChartContainer" class="row">
            @foreach ($charts as $chart)
                <div class="col-md-6">
                    <h3>{{ $chart['question']->question_text }}</h3>
                    <canvas id="chart_{{ $chart['question']->id }}" width="400" height="400"></canvas>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        @foreach ($charts as $chart)
        document.addEventListener('DOMContentLoaded', function () {
            const chart_{{ $chart['question']->id }} = document.getElementById('chart_{{ $chart['question']->id }}').getContext('2d');
            new Chart(chart_{{ $chart['question']->id }}, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($chart['options']) !!},
                    datasets: [{
                        label: 'Response Count',
                        data: {!! json_encode($chart['responseCounts']) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                        ], // Use the desired gradient by index
                        borderColor: 'transparent', // Set the border color to transparent
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            stepSize: 1,
                        }
                    }
                }
            });
        });
        @endforeach
    </script>
@endpush

