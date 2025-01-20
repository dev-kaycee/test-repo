@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
        }
        .chart-container {
            background-color: #EBEAEE;
            border-radius: 8px;
            padding: 15px;
        }
        h1, p {
            color: #ffffff;
        }
        .info-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            width: 23%;
            text-align: center;
            color: #ffffff;
        }
        .info-card-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .info-card-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-card-label {
            font-size: 14px;
        }
    </style>

    <div class="container">
        <div class="info-cards">
            <div class="info-card" style="background-color: #216592;">
                <div class="info-card-icon">📊</div>
                <div class="info-card-value">{{$totalProjects}}</div>
                <div class="info-card-label">Total Projects </div>
            </div>
            <div class="info-card" style="background-color: #4b8da0;">
                <div class="info-card-icon">🌱</div>
                <div class="info-card-value">{{$projectVegCount}}</div>
                <div class="info-card-label">Total Vegetation Management Projects</div>
            </div>
            <div class="info-card" style="background-color: #34495e;">
                <div class="info-card-icon">💰</div>
                <div class="info-card-value">R{{$totalRevenueString}}</div>
                <div class="info-card-label">Total Revenue</div>
            </div>
            <div class="info-card" style="background-color: #4f42b4;">
                <div class="info-card-icon">🎓</div>
                <div class="info-card-value">{{$totalStudents}}</div>
                <div class="info-card-label">Total Students Trained</div>
            </div>
        </div>

    <div class="container">

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); gap: 20px;">
            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="projectStatusChart"></canvas>
            </div>
            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="vehicleKmsChart"></canvas>
            </div>
            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="smmeStatusChart"></canvas>
            </div>
            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="AssetStatusChart"></canvas>
            </div>

            <div class="chart-container" style="width: 100%; height: 300px;">
                <canvas id="invoicesQuoteComparison"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var revenueChart = document.getElementById('revenueChart').getContext('2d');
            var months = @json($months);
            var threeMonthsInvoicedTotal = @json($threeMonthsInvoicedTotal);
            var threeMonthsQuotedTotal = @json($threeMonthsQuotedTotal);
            var threeMonthsActualBudgetTotal = @json($threeMonthsActualBudgetTotal);

            var inUseAssetsCount = @json($inUseAssetsCount);
            var availableAssetsCount = @json($availableAssetsCount);
            var inServiceAssetsCount = @json($inServiceAssetsCount)

            const chartConfig = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#000000'
                        }
                    },
                    title: {
                        color: '#000000',
                        font: {
                            size: 16
                        }
                    }
                }
            };

            // Project Status Overview
            new Chart(document.getElementById('projectStatusChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Vegetation', 'Training', 'Innovation', 'Planning'],
                    datasets: [
                        {
                            label: 'Completed',
                            data: [
                                {{ $projectCounts['vegetation-management'][$PROJECT_STATUS_COMPLETED] }},
                                {{ $projectCounts['training'][$PROJECT_STATUS_COMPLETED] }},
                                {{ $projectCounts['innovation'][$PROJECT_STATUS_COMPLETED] }},
                                {{ $projectCounts['planning'][$PROJECT_STATUS_COMPLETED] }}
                            ],
                            backgroundColor: '#4b8da0'
                        },
                        {
                            label: 'Ongoing',
                            data: [
                                {{ $projectCounts['vegetation-management'][$PROJECT_STATUS_IN_PROGRESS] }},
                                {{ $projectCounts['training'][$PROJECT_STATUS_IN_PROGRESS] }},
                                {{ $projectCounts['innovation'][$PROJECT_STATUS_IN_PROGRESS] }},
                                {{ $projectCounts['planning'][$PROJECT_STATUS_IN_PROGRESS] }}
                            ],
                            backgroundColor: '#216592'
                        },
                        {
                            label: 'Planned',
                            data: [
                                {{ $projectCounts['vegetation-management'][$PROJECT_STATUS_PLANNED] }},
                                {{ $projectCounts['training'][$PROJECT_STATUS_PLANNED] }},
                                {{ $projectCounts['innovation'][$PROJECT_STATUS_PLANNED] }},
                                {{ $projectCounts['planning'][$PROJECT_STATUS_PLANNED] }}
                            ],
                            backgroundColor: '#34495e'
                        }
                    ]

                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'Project Status Overview'
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#000000' }
                        },
                        y: {
                            ticks: { color: '#000000', stepSize: 1, },
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(document.getElementById('smmeStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Green', 'Yellow', 'Red'],
                    datasets: [{
                        data: [
                            {{ $smmeStatusCounts['green'] ?? 0 }},
                            {{ $smmeStatusCounts['yellow'] ?? 0 }},
                            {{ $smmeStatusCounts['red'] ?? 0 }}
                        ],
                        backgroundColor: ['#216592', '#4b8da0', '#34495e']
                    }]
                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'SMME Status Overview'
                        },
                        legend: {
                            display: true,
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '50%'
                },
            });

            new Chart(document.getElementById('vehicleKmsChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Actual', 'Vehicle Kms target'],
                    datasets: [{
                        data: [{{$vehicleActualKms}}, {{$vehicleTargetKms}}],
                        backgroundColor: ['#4b8da0', '#216592']
                    }]
                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'Vehicle Kms Distribution'
                        }
                    }
                }
            });

            // Revenue Overview
            new Chart(revenueChart, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Planned Revenue',
                            data: threeMonthsInvoicedTotal,
                            backgroundColor: '#216592'
                        },
                        {
                            label: 'Actual Revenue',
                            data: threeMonthsActualBudgetTotal,
                            backgroundColor: '#4b8da0'
                        }
                    ]
                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'Revenue Overview'
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#000000' } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#000000',
                                callback: function(value, index, values) {
                                    return 'R' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Training Management
            new Chart(document.getElementById('AssetStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['In Use', 'Available', 'In Service'],
                    datasets: [{
                        data: [inUseAssetsCount, availableAssetsCount, inServiceAssetsCount],
                        backgroundColor: ['#216592', '#4b8da0', '#34495e']
                    }]
                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'Assets Status Overview'
                        }
                    }
                }
            });

            // Invoiced vs Quoted
            new Chart(document.getElementById('invoicesQuoteComparison').getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Invoiced',
                            data: threeMonthsInvoicedTotal,
                            backgroundColor:'#216592',
                            borderColor: '#216592',
                            fill: false
                        },
                        {
                            label: 'Quoted',
                            data: threeMonthsQuotedTotal,
                            backgroundColor: '#4b8da0',
                            borderColor: '#4b8da0',
                            fill: false
                        }
                    ]
                },
                options: {
                    ...chartConfig,
                    plugins: {
                        ...chartConfig.plugins,
                        title: {
                            ...chartConfig.plugins.title,
                            display: true,
                            text: 'Invoiced and Quoted Amounts'
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#000000' } },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#000000',
                                callback: function(value, index, values) {
                                    return 'R' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            })


        });
    </script>
@endsection