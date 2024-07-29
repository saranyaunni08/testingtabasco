@extends('layouts.default', ['title' => $title, 'page' => $page])

@section('content')
<div class="container-fluid py-4">
    @foreach($floorRooms as $floor => $rooms)
    <div class="card my-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Floor: {{ $floor }}</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Door No</th>
                            <th class="text-center">Flat Model</th>
                            <th class="text-center">Super Build Up Area Sq Ft</th>
                            <th class="text-center">Super Build Up Area Rate</th>
                            <th class="text-center">Super Build Up Area Expected Amount</th>
                            <th class="text-center">Carpet Area Sq Ft</th>
                            <th class="text-center">Carpet Area Rate</th>
                            <th class="text-center">Carpet Area Expected Amount</th>
                            <th class="text-center">GST Amount</th>
                            <th class="text-center">Parking Amount</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Sale Amount (RS)</th>
                            <th class="text-center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $index => $room)
                        <tr>
                            <td class="text-center">{{ (int)$index + 1 }}</td>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->flat_model }}</td>
                            <td class="text-right">{{ $room->flat_build_up_area }}</td>
                            <td class="text-right">{{ $room->flat_super_build_up_price }}</td>
                            <td class="text-right">{{ $room->flat_expected_super_buildup_area_price }}</td>
                            <td class="text-right">{{ $room->flat_carpet_area }}</td>
                            <td class="text-right">{{ $room->flat_carpet_area_price }}</td>
                            <td class="text-right">{{ $room->flat_expected_carpet_area_price }}</td>
                            <td class="text-right">
                                @if($room->sales->isNotEmpty())
                                {{ $room->sales->first()->gst_amount }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($room->sales->isNotEmpty())
                                {{ $room->sales->first()->parking_amount }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($room->sales->isNotEmpty())
                                {{ $room->sales->first()->customer_name }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($room->sales->isNotEmpty())
                                {{ $room->sales->first()->sale_amount }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($room->sales->isNotEmpty())
                                {{ $room->sales->first()->total_amount }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
