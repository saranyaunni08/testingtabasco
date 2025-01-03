@extends('layouts.default')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Bank List</h3>

    <!-- Table to display the list of banks -->
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Serial Number</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Account Holder Name</th>
                            <th>IFSC Code</th>
                            <th>Branch</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Contact Number</th>
                            <th>Email Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banks as $bank)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bank->name }}</td>
                            <td>{{ $bank->account_number }}</td>
                            <td>{{ $bank->account_holder_name }}</td>
                            <td>{{ $bank->ifsc_code }}</td>
                            <td>{{ $bank->branch }}</td>
                            <td>{{ $bank->address }}</td>
                            <td>{{ $bank->city }}</td>
                            <td>{{ $bank->country }}</td>
                            <td>{{ $bank->contact_number }}</td>
                            <td>{{ $bank->email_address }}</td>
                            <td>
                                <a href="{{ route('admin.banks.edit', $bank->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this bank?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Back button to go to the create page -->
    <div class="mt-3 text-right">
        <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">Back</a>
    </div>
</div>

@endsection

