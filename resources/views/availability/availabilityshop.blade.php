@extends('layouts.default')

@section('content')
<div class="container">

    <div style="text-align: right;">
        <a href="{{ route('admin.availability_shop.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <!-- Title Row -->
            <tr>
                <th colspan="6"
                    style="text-align: center; background-color: #008080; color: white; padding: 10px; border: 1px solid #000; font-size: 20px;">
                    AVAILABILITY SHOP
                </th>
            </tr>
            <!-- Headers -->
            <tr>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">NO</th>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">FLOOR
                </th>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">TYPE
                </th>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">DOOR NO
                </th>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">
                    BUILT-UP AREA (In Sq Ft)</th>
                <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">CARPET
                    AREA (In Sq Ft)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($availability->where('room_type', 'Shops')->sortBy('room_floor') as $item) <!-- Sorting by room_floor -->
                <tr>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $loop->iteration }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_floor'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_type'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_number'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['build_up_area'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['carpet_area'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="border: 1px solid #ddd; text-align: right; padding: 10px; font-weight: bold;">
                    Total</td>
                <td style="border: 1px solid #ddd; text-align: center; padding: 10px; font-weight: bold;">
                    {{ number_format($totalBuildUpArea) }}
                </td>
                <td style="border: 1px solid #ddd; text-align: center; padding: 10px; font-weight: bold;">
                    {{ number_format($totalCarpetArea) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection