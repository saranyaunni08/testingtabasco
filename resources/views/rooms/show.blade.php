@extends('layouts.default', ['title' => 'Rooms'])

@section('content')
    <style>
        .card-heading {
            color: black;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .card-amount {
            color: black;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .link-blue {
            color: blue;
            text-decoration: none;
        }

        .link-blue:hover {
            text-decoration: none;
        }

        .sold-expected-info {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .sold-expected-info .info-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .sold-expected-info .info-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .sold-expected-info .amount-sold {
            font-size: 18px;
            font-weight: bold;
            color: green;
        }

        .sold-expected-info .amount-expected {
            font-size: 18px;
            font-weight: bold;
            color: rgb(0, 0, 0);
        }

        .sold-expected-info .profit-loss {
            font-size: 16px;
            text-align: center;
        }

        .total-build-up-area {
            color: rgba(255, 136, 0, 0.938);
        }

        .sold-build-up-area {
            color: green;
        }

        .balance-build-up-area {
            color: red;
        }

        .sold-amount {
            color: green;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Room Stats Cards -->
            @foreach ($roomStats as $type => $stats)
                <div class="col-xl-3 col-lg-4 col-sm-5 col-7 mb-4">
                    <a href="
                        @if ($type == 'Flat Expected Amount')
                            {{ route('admin.flats.index', ['building_id' => $building->id]) }}
                        @elseif ($type == 'Shops Expected Amount')
                            {{ route('admin.shops.index', ['building_id' => $building->id]) }}
                        @elseif ($type == 'Table space Expected Amount')
                            {{ route('admin.table-spaces.index', ['building_id' => $building->id]) }}
                        @elseif ($type == 'Kiosk Expected Amount')
                            {{ route('admin.kiosks.index', ['building_id' => $building->id]) }}
                        @elseif ($type == 'Chair space Expected Amount')
                            {{ route('admin.chair-spaces.index', ['building_id' => $building->id]) }}
                        @endif
                    " class="link-blue">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar">
                                        <img src="{{ asset('img/wallet.png') }}" alt="Total Expected Amount" class="rounded">
                                    </div>
                                </div>
                                <span class="card-heading">{{ $type }}</span>
                                <h4 class="card-amount">₹{{ number_format($stats['total'], 2) }}</h4>

                                @if ($type == 'Flat Expected Amount')
                                    <h4 class="card-heading">Total Build-Up Area</h4>
                                    <h4 class="card-amount total-build-up-area">
                                        {{ number_format($stats['totalBuildUpArea'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Build-Up Area</h4>
                                    <h4 class="card-amount sold-build-up-area">
                                        {{ number_format($stats['soldBuildUpArea'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Balance Build-Up Area for Sell</h4>
                                    <h4 class="card-amount balance-build-up-area">
                                        {{ number_format($stats['totalBuildUpArea'] - $stats['soldBuildUpArea'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Amount</h4>
                                    <h4 class="card-amount sold-amount">
                                        ₹{{ number_format($roomStats['Flat Expected Amount']['soldAmount'], 2) }}
                                    </h4>

                                    @if ($roomStats['Flat Expected Amount']['allFlatsSold'])
                                        <div class="sold-expected-info">
                                            <div class="info-header">Sold / Expected</div>
                                            <div class="info-content">
                                                <span class="amount-sold">₹{{ number_format($roomStats['Flat Expected Amount']['soldAmount'], 2) }}</span> /
                                                <span class="amount-expected">₹{{ number_format($roomStats['Flat Expected Amount']['total'], 2) }}</span>
                                            </div>
                                            <div class="profit-loss" style="color: {{ $roomStats['Flat Expected Amount']['profitOrLossColor'] }}">
                                                ({{ abs($roomStats['Flat Expected Amount']['profitOrLoss']) }} {{ $roomStats['Flat Expected Amount']['profitOrLossText'] }})
                                            </div>
                                        </div>
                                    @endif

                                    @elseif ($type == 'Shops Expected Amount')
                                    <h4 class="card-heading">Total Build-Up Area</h4>
                                    <h4 class="card-amount total-build-up-area">
                                        {{ number_format($totalShopBuildUpArea, 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Build-Up Area</h4>
                                    <h4 class="card-amount sold-build-up-area">
                                        {{ number_format($stats['soldShopBuildUpArea'], 2) }} sq ft
                                    </h4>
                                    
                                    <h4 class="card-heading">Balance Build-Up Area for Sell</h4>
                                    <h4 class="card-amount balance-build-up-area">
                                        {{ number_format($stats['totalShopBuildUpArea'] - $stats['allShopsSold'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Amount</h4>
                                    <h4 class="card-amount sold-amount">
                                        ₹{{ number_format($roomStats['Shops Expected Amount']['soldShopAmount'], 2) }}
                                    </h4>
                                @if ($roomStats['Shops Expected Amount']['allShopsSold'])
                                    <div class="sold-expected-info">
                                        <div class="info-header">Sold / Expected</div>
                                        <div class="info-content">
                                            <span class="amount-sold">₹{{ number_format($roomStats['Shops Expected Amount']['soldShopAmount'], 2) }}</span> /
                                            <span class="amount-expected">₹{{ number_format($roomStats['Shops Expected Amount']['total'], 2) }}</span>
                                        </div>
                                        <div class="profit-loss" style="color: {{ $roomStats['Shops Expected Amount']['profitOrLossColorShop'] }}">
                                            ({{ abs($roomStats['Shops Expected Amount']['profitOrLossShop']) }} {{ $roomStats['Shops Expected Amount']['profitOrLossTextShop'] }})
                                        </div>
                                    </div>
                                @endif


                                @elseif ($type == 'Table space Expected Amount')

                                    <h4 class="card-heading">Total Build-Up Area</h4>
                                    <h4 class="card-amount total-build-up-area">
                                        {{ number_format($totalTableBuildUpArea, 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Build-Up Area</h4>
                                    <h4 class="card-amount sold-build-up-area">
                                        {{ number_format($stats['soldTableBuildUpArea'], 2) }} sq ft
                                    </h4>
                                    
                                    <h4 class="card-heading">Balance Build-Up Area for Sell</h4>
                                    <h4 class="card-amount balance-build-up-area">
                                        {{ number_format($stats['totalTableBuildUpArea'] - $stats['allTableSold'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Amount</h4>
                                    <h4 class="card-amount sold-amount">
                                        ₹{{ number_format($roomStats['Table space Expected Amount']['soldTableAmount'], 2) }}
                                    </h4>

                                @if ($roomStats['Table space Expected Amount']['allTableSold'])
                                    <div class="sold-expected-info">
                                        <div class="info-header">Sold / Expected</div>
                                        <div class="info-content">
                                            <span class="amount-sold">₹{{ number_format($roomStats['Table space Expected Amount']['soldTableAmount'], 2) }}</span> /
                                            <span class="amount-expected">₹{{ number_format($roomStats['Table space Expected Amount']['total'], 2) }}</span>
                                        </div>
                                        <div class="profit-loss" style="color: {{ $roomStats['Table space Expected Amount']['profitOrLossColorTable'] }}">
                                            ({{ abs($roomStats['Table space Expected Amount']['profitOrLossTable']) }} {{ $roomStats['Table space Expected Amount']['profitOrLossTextTable'] }})
                                        </div>
                                    </div>
                                @endif



                                @elseif ($type == 'Kiosk Expected Amount')

                                    <h4 class="card-heading">Total Build-Up Area</h4>
                                    <h4 class="card-amount total-build-up-area">
                                        {{ number_format($totalKioskBuildUpArea, 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Build-Up Area</h4>
                                    <h4 class="card-amount sold-build-up-area">
                                        {{ number_format($stats['soldKioskBuildUpArea'], 2) }} sq ft
                                    </h4>
                                    
                                    <h4 class="card-heading">Balance Build-Up Area for Sell</h4>
                                    <h4 class="card-amount balance-build-up-area">
                                        {{ number_format($stats['totalKioskBuildUpArea'] - $stats['allKioskSold'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Amount</h4>
                                    <h4 class="card-amount sold-amount">
                                        ₹{{ number_format($roomStats['Kiosk Expected Amount']['soldKioskAmount'], 2) }}
                                    </h4>


                                    @if ($roomStats['Kiosk Expected Amount']['allKioskSold'])
                                    <div class="sold-expected-info">
                                        <div class="info-header">Sold / Expected</div>
                                        <div class="info-content">
                                            <span class="amount-sold">₹{{ number_format($roomStats['Kiosk Expected Amount']['soldKioskAmount'], 2) }}</span> /
                                            <span class="amount-expected">₹{{ number_format($roomStats['Kiosk Expected Amount']['total'], 2) }}</span>
                                        </div>
                                        <div class="profit-loss" style="color: {{ $roomStats['Kiosk Expected Amount']['profitOrLossColorKiosk'] }}">
                                            ({{ abs($roomStats['Kiosk Expected Amount']['profitOrLossKiosk']) }} {{ $roomStats['Kiosk Expected Amount']['profitOrLossTextKiosk'] }})
                                        </div>
                                    </div>
                                @endif

                                @elseif ($type == 'Chair space Expected Amount')

                                    <h4 class="card-heading">Total Build-Up Area</h4>
                                    <h4 class="card-amount total-build-up-area">
                                        {{ number_format($totalChairBuildUpArea, 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Build-Up Area</h4>
                                    <h4 class="card-amount sold-build-up-area">
                                        {{ number_format($stats['soldChairBuildUpArea'], 2) }} sq ft
                                    </h4>
                                    
                                    <h4 class="card-heading">Balance Build-Up Area for Sell</h4>
                                    <h4 class="card-amount balance-build-up-area">
                                        {{ number_format($stats['totalChairBuildUpArea'] - $stats['allChairSold'], 2) }} sq ft
                                    </h4>

                                    <h4 class="card-heading">Sold Amount</h4>
                                    <h4 class="card-amount sold-amount">
                                        ₹{{ number_format($roomStats['Chair space Expected Amount']['soldChairAmount'], 2) }}
                                    </h4>

                                    @if ($roomStats['Chair space Expected Amount']['allChairSold'])
                                    <div class="sold-expected-info">
                                        <div class="info-header">Sold / Expected</div>
                                        <div class="info-content">
                                            <span class="amount-sold">₹{{ number_format($roomStats['Chair space Expected Amount']['soldChairAmount'], 2) }}</span> /
                                            <span class="amount-expected">₹{{ number_format($roomStats['Chair space Expected Amount']['total'], 2) }}</span>
                                        </div>
                                        <div class="profit-loss" style="color: {{ $roomStats['Chair space Expected Amount']['profitOrLossColorChair'] }}">
                                            ({{ abs($roomStats['Chair space Expected Amount']['profitOrLossChair']) }} {{ $roomStats['Chair space Expected Amount']['profitOrLossTextChair'] }})
                                        </div>
                                    </div>
                                @endif
                                @endif

                                
                                    
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            <!-- Add the total expected amount card -->
            @if ($roomStats['Chair space Expected Amount']['count'] > 0)
                <div class="col-xl-3 col-lg-4 col-sm-5 col-7 mb-4">
                    <a href="{{ route('admin.chair-spaces.index', ['building_id' => $building->id]) }}" class="link-blue">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('img/wallet.png') }}" alt="Total Expected Amount" class="rounded">
                                    </div>
                                </div>
                                <span class="card-heading">Total Expected Amount</span>
                                <h4 class="card-amount">₹{{ number_format($totalExpectedAmount, 2) }}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
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
        <div class="container-fluid pt-4 px-3">
            <div class="row">
                <!-- Time Series Chart -->
                <div class="col-sm-12 col-md-12 col-xl-12 mb-4">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">Expected vs Sold Amount Over Time</h6>
                        <canvas id="time-series-chart" width="1000" height="400"></canvas>
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

        var ctxTimeSeries = document.getElementById('time-series-chart').getContext('2d');
var timeSeriesChart = new Chart(ctxTimeSeries, {
    type: 'line',
    data: {
        labels: @json(array_column($flatTimeSeries, 'time')),
        datasets: [
            {
                label: 'Flat Expected Amount',
                data: @json(array_column($flatTimeSeries, 'expected')),
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
            },
            {
                label: 'Flat Sold Amount',
                data: @json(array_column($flatTimeSeries, 'sold')),
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false,
            },
        ]
    },
    options: {
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        }
    }
});

    </script>
@endsection
