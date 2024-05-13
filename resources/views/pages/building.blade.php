@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Building A</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="col-md-12">
                            <h5 class="text-primary mb-3">Items in the Building:</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Floor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Flats</td>
                                            <td>4</td>
                                        </tr>
                                        <tr>
                                            <td>Shops</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>Supermarket</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Others</td>
                                            <td>5</td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Add a canvas element for the pie chart -->
                        <div class="col-md-6">
                            <canvas id="buildingChart" width="400" height="400"></canvas>
                        </div>
                        <!-- Add a canvas element for the histogram -->
                        <div class="col-md-6">
                            <canvas id="buildingHistogram" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Get the canvas element for the pie chart
    var pieCtx = document.getElementById('buildingChart').getContext('2d');

    // Define data for the pie chart
    var pieData = {
        labels: ['Sold', 'Remaining'],
        datasets: [{
            data: [45, 55], // Sample data, you can replace this with actual data
            backgroundColor: ['#28a745', '#ffc107'], // Colors for each segment
        }]
    };

    // Create a new pie chart
    var pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true, // Make the chart responsive
            maintainAspectRatio: false, // Maintain aspect ratio
            legend: {
                position: 'bottom' // Position of the legend
            }
        }
    });

    // Get the canvas element for the histogram
    var histogramCtx = document.getElementById('buildingHistogram').getContext('2d');

    // Define data for the histogram
    var histogramData = {
        labels: ['Flates', 'Super Market', 'Shops', 'Others'],
        datasets: [{
            label: 'Sales Report',
            data: [12, 19, 3, 5, 2], // Sample data, you can replace this with actual data
            backgroundColor: '#007bff' // Color for the bars
        }]
    };

    // Create a new histogram
    var histogram = new Chart(histogramCtx, {
        type: 'bar',
        data: histogramData,
        options: {
            responsive: true, // Make the chart responsive
            maintainAspectRatio: false, // Maintain aspect ratio
            legend: {
                display: false // Hide the legend
            }
        }
    });
</script>

@endsection
