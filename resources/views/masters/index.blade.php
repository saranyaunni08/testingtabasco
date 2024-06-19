@extends('layouts.default', ['title' => 'Master Settings'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <h6 class="text-white text-capitalize">Master Settings</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="col-12">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ route('admin.masters.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="gst_flat">GST for Flat (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="gst_flat" name="gst_flat" value="{{ $settings->gst_flat ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="gst_shop">GST for Shop (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="gst_shop" name="gst_shop" value="{{ $settings->gst_shop ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="advance_payment_days">Advance Payment Days</label>
                                    <input type="number" class="form-control" id="advance_payment_days" name="advance_payment_days" value="{{ $settings->advance_payment_days ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="parking_fixed_rate">Parking Fixed Rate per Slot</label>
                                    <input type="number" step="0.01" class="form-control" id="parking_fixed_rate" name="parking_fixed_rate" value="{{ $settings->parking_fixed_rate ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="parking_rate_per_sq_ft">Parking Rate per Sq Ft</label>
                                    <input type="number" step="0.01" class="form-control" id="parking_rate_per_sq_ft" name="parking_rate_per_sq_ft" value="{{ $settings->parking_rate_per_sq_ft ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
