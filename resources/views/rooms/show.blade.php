@extends('layouts.default', ['title' => 'Rooms'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Room Stats Cards -->
            @foreach ($roomStats as $type => $stats)
                <div class="col-xl-3 col-lg-4 col-sm-5 col-7 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('img/image.png') }}" alt="Credit Card" class="rounded">
                                </div>
                            </div>
                            <span class="fw-medium d-block mb-1">{{ $type }}</span>
                            <h4 class="card-title mb-2">₹{{ number_format($stats['total'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Charts -->
        <div class="container-fluid pt-4 px-3">

        <div class="row">
            <!-- Small Donut Chart -->
            <div class="col-sm-12 col-md-6 col-xl-4 mb-4">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Room Types Distribution</h6>
                    <canvas id="doughnut-chart" height="200"></canvas> <!-- Reduced height for a smaller chart -->
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-sm-12 col-md-6 col-xl-8 mb-4">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Sold Amount and Expected Price</h6>
                  <canvas id="bar-chart" width="1000" height="487" style="display: block; box-sizing: border-box; height: 389.6px; width: 780px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Donut chart
            var ctxPie = document.getElementById('doughnut-chart').getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Flats', 'Shops', 'Table Spaces', 'Kiosks', 'Chair Spaces'],
                    datasets: [{
                        label: 'Room Types',
                        data: [
                            {{ $totalFlats }},
                            {{ $totalShops }},
                            {{ $totalTableSpaces }},
                            {{ $totalKiosks }},
                            {{ $totalChairSpaces }},
                        ],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF5733']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Bar chart
            var ctxBar = document.getElementById('bar-chart').getContext('2d');
            var myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach ($buildings as $building)
                            '{{ strtoupper($building->building_name) }}',
                        @endforeach
                    ],
                    datasets: [
                        {
                            label: 'Sold Amount',
                            data: @json($soldAmountData),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Expected Price',
                            data: @json($expectedPriceData),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
