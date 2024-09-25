@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="content">
    <main class="main">
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center p-4 h-100">
                        <i class="fa fa-chart-line fa-4x text-primary mb-3"></i>
                        <div class="text-center">
                            <p class="mb-2">Expected Amount</p>
                            <h5 class="font-weight-bolder">₹{{ number_format($ExpectedPrice) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center p-4 h-100">
                        <i class="fa fa-credit-card fa-4x text-primary mb-3"></i>
                        <div class="text-center">
                            <p class="mb-2">Total EMI Received</p>
                            <h5 class="font-weight-bolder">₹{{ number_format($totalEmiReceived) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center p-4 h-100">
                        <i class="fa fa-money-bill-wave fa-4x text-primary mb-3"></i>
                        <div class="text-center">
                            <p class="mb-2">Total Advance Amount</p>
                            {{-- <h5 class="font-weight-bolder">₹{{ number_format($totalAdvanceAmount) }}</h5> --}}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center p-4 h-100">
                        <i class="fa fa-users fa-3x text-primary mb-3"></i>
                        <div class="text-center">
                            <p class="mb-2">Total Customers</p>
                            <h5 class="font-weight-bolder">
                                {{-- {{ $totalCustomers }} --}}
                            </h5>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>

        
        

        <!-- Rest of your code -->

        <div class="container-fluid pt-4 px-3">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-5">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">TOTAL ROOMS</h6>
                        <canvas id="doughnut-chart" height="400"></canvas>
                    </div>
                </div>

                <div class="col-sm-12 col-xl-6">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4"></h6><br><br>
                        <canvas id="bar-chart" width="1000" height="487" style="display: block; box-sizing: border-box; height: 389.6px; width: 780px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </main>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie chart
        var ctxPie = document.getElementById('doughnut-chart').getContext('2d');
        var myPieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Flats', 'Shops', 'Kiosks', 'Chair Spaces', 'Table Spaces'],
                datasets: [{
                    label: 'Room Types',
                    data: [
                        {{ $totalFlats }},
                        {{ $totalShops }},
                        {{ $totalKiosks }},
                        {{ $totalChairSpaces }},
                        {{ $totalTableSpaces }},
                    ],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF5733']
                }]
            },
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
                        data: @json(array_values($expectedAmountByBuilding)),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>