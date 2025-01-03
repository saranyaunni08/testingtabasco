@extends('layouts.default')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Edit Bank Details</h3>

    <!-- Form to edit bank details -->
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Bank Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $bank->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="account_number">Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number', $bank->account_number) }}" required>
                </div>

                <div class="form-group">
                    <label for="account_number">Account Holder Name</label>
                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name', $bank->account_holder_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="ifsc_code">IFSC Code</label>
                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code', $bank->ifsc_code) }}" required>
                </div>

                <div class="form-group">
                    <label for="branch">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" value="{{ old('branch', $bank->branch) }}" required>
                </div>

                <div class="form-group">
                    <label for="address">Bank Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address',$bank->address)}}" required>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $bank->city) }}" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $bank->country) }}" required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $bank->contact_number) }}" required>
                </div>

                <div class="form-group">
                    <label for="email_address">Email Address</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" value="{{ old('email_address', $bank->email_address) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Bank</button>
                <a href="{{ route('admin.banks.views') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection


