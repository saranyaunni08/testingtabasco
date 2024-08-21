@extends('layouts.default', ['title' => 'Table Spaces Difference', 'page' => 'table-spaces'])

@section('content')

<div class="container-fluid py-4">
    <div class="card my-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Building: {{ $building->name }} - Table Spaces</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableSpacesTable" class="table table-bordered" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Door No</th>
                            <th class="text-center">Table Space Name</th>
                            <th class="text-center">Table Space Area Sq Ft</th>
                            <th class="text-center">Table Space Rate</th>
                            <th class="text-center">Table Space Expected Amount</th>
                            <th class="text-center">GST Amount</th>
                            <th class="text-center">Parking Amount</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Sale Amount (RS)</th>
                            <th class="text-center">Total Amount</th>
                            @if($tableSpacesData->contains(fn($data) => !empty($data->status)))
                                <th class="text-center">Status</th>
                            @endif
                            @if(!$tableSpacesData->contains(fn($data) => !empty($data->status)))
                                <th class="text-center">Difference</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableSpacesData as $index => $data)
                        <tr>
                            <td class="text-center">{{ (int)$index + 1 }}</td>
                            <td>{{ $data->room_number }}</td>
                            <td>{{ $data->space_name }}</td>
                            <td class="text-right">{{ $data->space_area }}</td>
                            <td class="text-right">{{ $data->space_rate }}</td>
                            <td class="text-right">{{ $data->space_expected_price }}</td>
                            <td class="text-right">
                                @if($data->sales->isNotEmpty())
                                    {{ $data->sales->first()->gst_amount }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-right">
                                @if($data->sales->isNotEmpty())
                                    {{ $data->sales->first()->parking_amount }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($data->sales->isNotEmpty())
                                    {{ $data->sales->first()->customer_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($data->sale_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($data->total_amount, 2) }}</td>
                            @if($data->show_difference)
                                <td class="text-right">
                                    @if($data->is_positive)
                                        <span style="color: green;">+{{ number_format($data->difference, 2) }}</span>
                                    @else
                                        <span style="color: red;">-{{ number_format(abs($data->difference), 2) }}</span>
                                    @endif
                                </td>
                            @endif
                            @if(isset($data->status))
                                <td class="text-center">{{ $data->status }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
