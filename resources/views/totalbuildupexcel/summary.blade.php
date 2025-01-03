@extends('layouts.default')

@section('content')
<div class="container">
    <div style="text-align: right;">
        <a href="{{ route('admin.summary_break_up.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">Summary</h3>

            <!-- SQ.FT SUMMARY -->
            <div class="mt-4">
                <table class="table table-bordered text-center">
                    <thead class="table-primary">
                        <tr>
                            <th colspan="3">SQ.FT SUMMARY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Apartment Total SQ.FT</td>
                            <td colspan="2">{{ number_format($totalSqFt, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Apartment Total SQ.FT Sold</td>
                            <td colspan="2">{{ number_format($totalSqFtSold, 2) }}</td>
                        </tr>

                        <tr>
                            <td>Apartment Balance SQ.FT</td>
                            <td colspan="2">{{ number_format($balanceSqFt, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Commercial Total SQ.FT</td>
                            <td colspan="2">{{ number_format($commercialTotalSqft, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Commercial Total SQ.FT Sold</td>
                            <td colspan="2">{{ number_format($commercialTotalSqftSold, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Commercial Balance SQ.FT</td>
                            <td colspan="2">{{ number_format($commercialBalanceSqft, 2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- FINANCIALS SUMMARY -->
            <div class="mt-4">
                <table class="table table-bordered text-center">
                    <thead class="table-success">
                        <tr>
                            <th colspan="3">FINANCIALS SUMMARY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Apartment Expected Sale</td>
                            <td colspan="2">{{number_format($totalExpectedAmount, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Parking Expected Sale</td>
                            <td colspan="2">{{number_format($ParkingtotalExpectedSale, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Commercial Expected Sale</td>
                            <td colspan="2">{{number_format($totalcommercialExpectedAmount, 2)}}</td>
                        </tr>
                        <tr class="table-warning">
                            <td>Total Expected Sale (a+b+c)</td>
                            <td colspan="2">{{number_format($totalExpectedSale, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Apartments Sold</td>
                            <td colspan="2">{{number_format($apartmentsSold, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Parking Sold</td>
                            <td colspan="2">{{number_format($parkingSold, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Commercial Sold</td>
                            <td colspan="2">{{number_format($commercialSold, 2)}}</td>
                        </tr>
                        <tr class="table-info">
                            <td>Total Sold</td>
                            <td colspan="2">{{number_format($totalSold, 2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection