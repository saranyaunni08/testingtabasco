@extends('layouts.default', ['title' => 'Sell Room'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <h6 class="text-white text-capitalize">Sell Room</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <form action="{{ route('admin.rooms.sell', $room->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="confirmSell" class="form-label">Are you sure you want to sell this room?</label>
                                <div>{{ $room->room_type }}</div>
                            </div>

                            <!-- Additional fields if necessary -->
                            <!-- Example: Sale Price -->
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" required>
                            </div>

                            <button type="submit" class="btn btn-success">Confirm Sell</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
