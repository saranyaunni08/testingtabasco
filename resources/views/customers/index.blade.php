<!-- resources/views/admin/customers/index.blade.php -->

@extends('layouts.default', ['title' => 'Customers', 'page' => 'customers'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-white mb-0">Customers</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="row p-4">
                            <div class="table-responsive mb-4">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sl. No</th>
                                            <th>Customer Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customerNames as $index => $customerName)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $customerName }}</td>
                                                <td>
                                                    <a href="{{ route('admin.customers.show', $customerName) }}" class="btn btn-info btn-sm">View</a>
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
