@extends('layouts.default')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-5">Sale Details for {{ $sale->customer_name }}</h2>
    <!-- Include the detailed table structure here, using the variables passed from the controller -->
    <!-- Example: -->
    <table class="table table-bordered shadow-lg">
        <thead class="bg-dark text-white text-center">
            <tr>
                <th colspan="2">Customer Details Overview</th>
                <!-- Cancel Button -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    Cancel Sale
                </button>
            </tr>
        </thead>
        <tbody>
            <!-- Populate table rows with data -->
        </tbody>
    </table>
    <!-- Include Cancel Modal -->
</div>
@endsection
