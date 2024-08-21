@extends('layouts.default', ['title' => 'Chair Spaces Difference', 'page' => 'chair-spaces'])

@section('content')

<div class="container-fluid py-4">
    <div class="card my-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Building: {{ $building->name }} - Chair Spaces</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="chairSpacesTable" class="table table-bordered" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Door No</th>
                            <th class="text-center">Chair Space Name</th>
                            <th class="text-center">Chair Space Area Sq Ft</th>
                            <th class="text-center">Chair Space Rate</th>
                            <th class="text-center">Chair Space Expected Amount</th>
                            <th class="text-center">GST Amount</th>
                            <th class="text-center">Parking Amount</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Sale Amount (RS)</th>
                            <th class="text-center">Total Amount</th>
                            @if($chairSpacesData->contains(fn($data) => !empty($data->status)))
                                <th class="text-center">Status</th>
                            @endif
                            @if(!$chairSpacesData->contains(fn($data) => !empty($data->status)))
                                <th class="text-center">Difference</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chairSpacesData as $index => $data)
                        <tr>
                            <td class="text-center">{{ (int)$index + 1 }}</td>
                            <td>{{ $data->room_number }}</td>
                            <td>{{ $data->chair_name }}</td>
                            <td class="text-right">{{ $data->chair_space_in_sq }}</td>
                            <td class="text-right">{{ $data->chair_space_rate }}</td>
                            <td class="text-right">{{ $data->chair_space_expected_rate }}</td>
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
