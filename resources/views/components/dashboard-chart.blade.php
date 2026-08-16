@props(['chart'])

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $chart['title'] }}</h3>
    
    @if($chart['type'] === 'pie')
        <div id="chart-{{ Str::slug($chart['title']) }}" class="w-full h-64"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: @json($chart['data']['series']),
                    chart: {
                        type: 'pie',
                        height: 250
                    },
                    labels: @json($chart['data']['labels']),
                    colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'],
                    legend: {
                        position: 'bottom'
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };
                
                var chart = new ApexCharts(document.querySelector("#chart-{{ Str::slug($chart['title']) }}"), options);
                chart.render();
            });
        </script>
    @elseif($chart['type'] === 'donut')
        <div id="chart-{{ Str::slug($chart['title']) }}" class="w-full h-64"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: @json($chart['data']['series']),
                    chart: {
                        type: 'donut',
                        height: 250
                    },
                    labels: @json($chart['data']['labels']),
                    colors: ['#10B981', '#EF4444', '#F59E0B'],
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%'
                            }
                        }
                    }
                };
                
                var chart = new ApexCharts(document.querySelector("#chart-{{ Str::slug($chart['title']) }}"), options);
                chart.render();
            });
        </script>
    @elseif($chart['type'] === 'bar')
        <div id="chart-{{ Str::slug($chart['title']) }}" class="w-full h-64"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: @json($chart['data']['series']),
                    chart: {
                        type: 'bar',
                        height: 250,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: @json($chart['data']['labels']),
                    },
                    yaxis: {
                        title: {
                            text: 'Count'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val
                            }
                        }
                    }
                };
                
                var chart = new ApexCharts(document.querySelector("#chart-{{ Str::slug($chart['title']) }}"), options);
                chart.render();
            });
        </script>
    @elseif($chart['type'] === 'line')
        <div id="chart-{{ Str::slug($chart['title']) }}" class="w-full h-64"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: @json($chart['data']['series']),
                    chart: {
                        type: 'line',
                        height: 250,
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    xaxis: {
                        categories: @json($chart['data']['labels']),
                    },
                    yaxis: {
                        title: {
                            text: 'Value'
                        }
                    },
                    colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                    markers: {
                        size: 4
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    },
                };
                
                var chart = new ApexCharts(document.querySelector("#chart-{{ Str::slug($chart['title']) }}"), options);
                chart.render();
            });
        </script>
    @elseif($chart['type'] === 'timeline')
        <div class="space-y-4">
            @foreach($chart['data'] as $activity)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $activity['type'] }}</p>
                    <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

