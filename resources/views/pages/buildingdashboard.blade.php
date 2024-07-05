@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3" style="text-transform: capitalize">Total Buildings</h6>
                        <a href="{{ route('admin.addbuilding') }}" class="btn btn-light">Add Building</a>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="container-fluid mt-3">
                            <div class="row">
                                @foreach($buildings as $building)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                    <div class="card shadow">
                                        <a href="{{ route('admin.rooms.index', ['building_id' => $building->id]) }}">
                                            <div class="card-body">
                                                <h4 class="card-title text-black" style="text-transform: capitalize">{{ $building->building_name }}</h4>

                                                @php
                                                $soldRoomsCount = $building->rooms()->where('status', 'sold')->count();
                                                $availableRoomsCount = $building->rooms()->where('status', 'available')->count();
                                                $totalRoomsCount = $soldRoomsCount + $availableRoomsCount;

                                                if ($totalRoomsCount == 0) {
                                                    $soldPercentage = 0;
                                                    $availablePercentage = 0;
                                                    $roomsStatus = "Empty"; 
                                                } else {
                                                    $soldPercentage = ($soldRoomsCount / $totalRoomsCount) * 100;
                                                    $availablePercentage = 100 - $soldPercentage;
                                                    $roomsStatus = "";
                                                }

                                                // Get unique room types in the building
                                                $roomTypes = $building->rooms()->distinct()->pluck('room_type')->toArray();

                                                // Prepare data for chart
                                                $chartLabels = [];
                                                $chartData = [];
                                                foreach ($roomTypes as $roomType) {
                                                    $count = $building->rooms()->where('room_type', $roomType)->count();
                                                    $chartLabels[] = ucfirst($roomType);
                                                    $chartData[] = $count;
                                                }
                                                $chartLabels = json_encode($chartLabels);
                                                $chartData = json_encode($chartData);
                                                @endphp

                                                <canvas id="buildingChart_{{ $building->id }}" style="width: 100%; height: 200px;"></canvas>

                                                <hr>

                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="text-gray mb-1">Sold</p>
                                                        <p class="text-gray mb-0">{{ $soldPercentage }}%</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray mb-1">Available</p>
                                                        <p class="text-gray mb-0">{{ $availablePercentage }}%</p>
                                                    </div>
                                                </div>
                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $soldPercentage }}%" aria-valuenow="{{ $soldPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $availablePercentage }}%" aria-valuenow="{{ $availablePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <p class="text-gray mb-0">{{ $roomsStatus }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Script for generating chart -->
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var ctx = document.getElementById('buildingChart_{{ $building->id }}').getContext('2d');
                                        var myChart = new Chart(ctx, {
                                            type: 'line',
                                            data: {
                                                labels: {!! $chartLabels !!},
                                                datasets: [{
                                                    label: 'Units',
                                                    data: {!! $chartData !!},
                                                    fill: 'origin', // to create the shaded area
                                                    backgroundColor: 'rgba(153, 102, 255, 0.2)', // light purple fill
                                                    borderColor: 'rgba(153, 102, 255, 1)', // purple border
                                                    borderWidth: 2,
                                                    pointBackgroundColor: 'rgba(153, 102, 255, 1)',
                                                    pointBorderColor: '#fff',
                                                    pointHoverBackgroundColor: '#fff',
                                                    pointHoverBorderColor: 'rgba(153, 102, 255, 1)',
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        display: true,
                                                        position: 'top',
                                                        labels: {
                                                            boxWidth: 20,
                                                            padding: 15,
                                                            font: {
                                                                size: 14
                                                            },
                                                            usePointStyle: true
                                                        }
                                                    },
                                                },
                                                scales: {
                                                    x: {
                                                        grid: {
                                                            display: false
                                                        },
                                                        ticks: {
                                                            maxRotation: 90,
                                                            minRotation: 45
                                                        }
                                                    },
                                                    y: {
                                                        beginAtZero: true,
                                                        grid: {
                                                            color: 'rgba(153, 102, 255, 0.1)'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
