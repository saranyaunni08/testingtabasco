@extends('layouts.default', ['title' => $title, 'page' => $page])

@section('content')

<div class="container-fluid py-4">
    @foreach($kiosks as $floor => $floorKiosks)
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
                            <th class="text-center">Kiosk Type</th>
                            <th class="text-center">Kiosk Name</th>
                            <th class="text-center">Kiosk Area Sq Ft</th>
                            <th class="text-center">Kiosk Area Rate</th>
                            <th class="text-center">Kiosk Expected Amount</th>
                            <th class="text-center">GST Amount</th>
                            <th class="text-center">Parking Amount</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Sale Amount (RS)</th>
                            <th class="text-center">Total Amount</th>
                            @if($floorKiosks->contains(fn($kiosk) => !empty($kiosk->status)))
                                <th class="text-center">Status</th>
                            @endif
                            @if(!$floorKiosks->contains(fn($kiosk) => !empty($kiosk->status)))
                                <th class="text-center">Difference</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($floorKiosks as $index => $kiosk)
                        @php
                            // Calculate total amount and sale amount from the sales relationship
                            $totalAmount = $kiosk->sales->sum('total_amount');
                            $saleAmount = $kiosk->sales->sum('sale_amount');
                            $expectedAmount = $kiosk->kiosk_expected_price;
                            $difference = $totalAmount - $expectedAmount;
                            $isPositive = $difference > 0;
                            $showDifference = empty($kiosk->status);
                        @endphp
                        <tr>
                            <td class="text-center">{{ (int)$index + 1 }}</td>
                            <td>{{ $kiosk->room_number }}</td>
                            <td>{{ $kiosk->kiosk_type }}</td>
                            <td>{{ $kiosk->kiosk_name }}</td>
                            <td class="text-right">{{ $kiosk->kiosk_area }}</td>
                            <td class="text-right">{{ $kiosk->kiosk_rate }}</td>
                            <td class="text-right">{{ $kiosk->kiosk_expected_price }}</td>
                            <td class="text-right">
                                @if($kiosk->sales->isNotEmpty())
                                {{ $kiosk->sales->first()->gst_amount }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($kiosk->sales->isNotEmpty())
                                {{ $kiosk->sales->first()->parking_amount }}
                                @endif
                            </td>
                            <td>
                                @if($kiosk->sales->isNotEmpty())
                                {{ $kiosk->sales->first()->customer_name }}
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($saleAmount, 2) }}</td>
                            <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                            @if($showDifference)
                                <td class="text-right">
                                    @if($isPositive)
                                        <span style="color: green;">+{{ number_format($difference, 2) }}</span>
                                    @else
                                        <span style="color: red;">-{{ number_format(abs($difference), 2) }}</span>
                                    @endif
                                </td>
                            @endif
                            <td class="text-center">{{ $kiosk->status }}</td> <!-- Status Data -->
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
