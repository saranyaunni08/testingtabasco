@extends('layouts.default')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <!-- Left side: Add New Bank -->
        <h3>Add New Bank</h3>
        
        <!-- Right side: View Bank List -->
        <a href="{{ route('admin.banks.views') }}" class="btn btn-secondary">View Bank List</a>
    </div>

    <!-- Form to add bank details -->
    <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        

        <div class="card">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name">Bank Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="account_number">Account Number</label>
                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                    @error('account_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="account_number">Account Holder Name</label>
                    <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}" required>
                    @error('account_holder_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>



                <div class="form-group mb-3">
                    <label for="ifsc_code">IFSC Code</label>
                    <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" required>
                    @error('ifsc_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="branch">Branch</label>
                    <input type="text" class="form-control @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{ old('branch') }}" required>
                    @error('branch')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="address">Bank Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="city">City</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                    @error('city')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="country">Country</label>
                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}">
                    @error('country')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                    @error('contact_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email_address">Email Address</label>
                    <input type="email" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address') }}">
                    @error('email_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Bank</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

