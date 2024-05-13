@extends('layouts.default', ['title' => 'Add New Building', 'page' => 'building'])

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3">Add Floor</h6>
            <div class="pe-3">
              <a href="{{ route('admin.addfloor') }}" ></a>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="row p-4">
            <form action="{{ route('admin.addbuilding.store') }}" method="POST">
              @csrf
              <div class="row justify-content-center">
                <div class="col-lg-8">
                  <div class="row">
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Floor Details Form</title>
                    </head>
                    <body>
                        <h2>Floor Details</h2>
                        <form action="#" method="post">
                        <div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Floor</label>
        <input name="floor" type="text" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Type</label>
        <input name="type" type="text" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Super Built-up Area (In Sq)</label>
        <input name="super_built_up_area" id="super_built_up_area" type="text" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Minus %</label>
        <input name="minus_percentage" id="minus_percentage" type="text" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Minus In Sq Carpet Area (In Sq)</label>
        <input name="minus_carpet_area" id="minus_carpet_area" type="text" class="form-control">
    </div>
</div>

<script>
    // JavaScript function to calculate Super Built-up Area
    function calculateSuperBuiltUpArea() {
        var superBuiltUpArea = parseFloat(document.getElementById('super_built_up_area').value);
        var minusPercentage = parseFloat(document.getElementById('minus_percentage').value);
        var minusCarpetArea = parseFloat(document.getElementById('minus_carpet_area').value);

        if (!isNaN(superBuiltUpArea) && !isNaN(minusPercentage) && !isNaN(minusCarpetArea)) {
            var calculatedArea = superBuiltUpArea - (superBuiltUpArea * (minusPercentage / 100)) - minusCarpetArea;
            document.getElementById('calculated_area').value = calculatedArea.toFixed(2);
        }
    }

    // Call the function when input fields change
    document.getElementById('super_built_up_area').addEventListener('input', calculateSuperBuiltUpArea);
    document.getElementById('minus_percentage').addEventListener('input', calculateSuperBuiltUpArea);
    document.getElementById('minus_carpet_area').addEventListener('input', calculateSuperBuiltUpArea);
</script>

<div class="col-md-6">
    <div class="input-group input-group-dynamic mb-4">
        <label class="form-label">Calculated Super Built-up Area (In Sq)</label>
        <input name="calculated_area" id="calculated_area" type="text" class="form-control" readonly>
    </div>
</div>


@endsection




                           


