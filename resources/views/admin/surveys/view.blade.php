@extends('admin.layouts.app')
@section('title', 'Question Create')

@section('content')


    @include('admin.includes._breadcrumb', ['title' => "Survey $survey->title", 'items' => ['Home' => route('admin.dashboard'), 'surveys' => route('admin.surveys.list'), 'Survey' => null]])
    <div class="row">
        <div id="surveyChartContainer" class="row">
            <div class="text-center">
                <p style="font-size: 1rem; font-weight: bold">Maximum participants: {{$survey->max_participants}}</p>
                <p style="font-size: 1rem; font-weight: bold">Total participants: {{$survey->answers()->distinct('user_id')->count()}}</p>

            </div>
            <div class="row align-items-center justify-content-between">
                @if(count($charts) > 0)
                <div class="col col-md-6">
                    <label for="chartType">Select Chart Type</label><select id="chartType" class="form-select form-select-lg mb-3">
                        <option value="pie">Pie Chart</option>
                        <option value="bar">Bar Chart</option>
                        <!-- Add more options for other chart types if needed -->
                    </select>@endif
                </div>
                    @if(count($charts) > 0)
                <div class="col col-md-6 text-lg-end">
                        <button class="btn btn-primary" id="downloadButton">
                            Export Chart
                        </button>


                </div>
                    @endif
            </div>



                @if(count($charts) > 0)
                <div id="contentChart" class="row">
                    @foreach ($charts as $chart)


                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <h3>{{ $chart['question']->question_text }}</h3>
                            </div>

                            <canvas id="chart_{{ $chart['question']->id }}" width="400" height="400"></canvas>
                        </div>



                    @endforeach
                </div>

                @else


                <div class="">
                   <p style="font-size: 30px">No one has joined this survey yet</p>
                </div>
            @endif

        </div>
    </div>
@endsection


@push('scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        @foreach ($charts as $chart)

                document.addEventListener('DOMContentLoaded', function () {

                    const chart_{{ $chart['question']->id }} = document.getElementById('chart_{{ $chart['question']->id }}').getContext('2d');
                    let currentChart;
                    function createChart(chartType){
                        if (currentChart) {
                            currentChart.destroy();
                        }
                        currentChart =  new Chart(chart_{{ $chart['question']->id }}, {
                            type: chartType,
                            data: {
                                labels: {!! json_encode($chart['options']) !!},
                                datasets: [{
                                    label: 'Response Count',
                                    data: {!! json_encode($chart['responseCounts']['percentage']) !!},
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.8)',
                                        'rgba(255,86,207,0.8)',
                                        'rgba(192,173,75,0.8)',
                                        'rgba(99,255,193,0.8)',
                                    ], // Use the desired gradient by index
                                    borderColor: 'transparent', // Set the border color to transparent
                                }]
                            },
                            options: {
                                plugins: {
                                    datalabels: {
                                        formatter: (value, context) => {
                                            var data = {!! json_encode($chart['responseCounts']['total']) !!}
                                            const total =  data[context?.dataIndex]
                                            console.log(value)
                                            if (value === '0.00'){
                                                return ""
                                            }
                                            return `${value}%`;
                                        },
                                        color: '#000', // Set the color for the labels
                                        font: {
                                            size: 14    ,
                                        },
                                    },
                                },
                                scales: {
                                    y: {
                                        display: false, // Set the y-axis to not display
                                    },
                                },
                            },

                            plugins: [ChartDataLabels],
                        });
                    }

                    const selectBox = document.getElementById('chartType');
                    selectBox.addEventListener('change', (event) => {
                        const chartType = event.target.value;
                        createChart(chartType);
                    });

                    // Create the initial chart with the default chart type (e.g., 'pie')
                    createChart('pie');
                });
        @endforeach


        // Function to capture the content of the div as an image
        function captureAndDownload() {
            const divToCapture = document.getElementById('contentChart');

            // Use HTML2Canvas to capture the content
            html2canvas(divToCapture).then(function(canvas) {
                // Create a temporary anchor element for downloading
                const downloadLink = document.createElement('a');
                downloadLink.href = canvas.toDataURL('image/png');
                downloadLink.download = '{{$survey->title}}.png';

                // Trigger a click event to initiate the download
                downloadLink.click();
            });
        }

        // Attach the captureAndDownload function to a button click event
        document.getElementById('downloadButton').addEventListener('click', captureAndDownload);

    </script>
@endpush
