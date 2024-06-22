@extends('layouts.default', ['title' => 'Customers', 'page' => 'customers'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4 mx-3">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title text-white mb-0">Customers</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header flex-column flex-md-row">
                                <div class="head-label text-center">
                                    <h5 class="card-title mb-0">Total Customers</h5>
                                </div>
                            </div>
                            <!-- DataTable content start -->
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex">
                                        <input type="search" name="search" class="form-control" placeholder="Search by name" value="{{ request()->get('search') }}">
                                        <button type="submit" class="btn btn-info ms-2" style="width: 100px">Search</button>
                                    </form>
                                </div>
                            </div>
                            <table class="table table-bordered dataTable no-footer" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>sl.no</th>
                                        <th>Name</th>
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
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">
                                        Showing {{ $sales->count() }} of {{ $sales->total() }} entries
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                        {{ $sales->appends(['search' => request()->get('search')])->links() }}
                                    </div>
                                </div>
                            </div>
                            <!-- DataTable content end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
