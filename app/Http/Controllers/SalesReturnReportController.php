<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\SaleReturn;
use Barryvdh\DomPDF\Facade as Pdf;
class SalesReturnReportController extends Controller
{
    public function returnreportall($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        $salesReturns = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'shops') // Filter for room_type = 'shops'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'shops'); // Ensure only rooms of type 'shops' are included
        })->get();

        $salesFlats = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Flats') // Filter for room_type = 'flats'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'flat_build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Flats');
        })->get();
        // Fetch parking details
        $parkingDetails = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'parking_id', 'sale_amount', 'customer_name', 'status')
                    ->whereNotNull('parking_id'); // Ensure parking_id is not null
            },
            'sale.parking' => function ($query) {
                $query->select('id', 'slot_number', 'parking_floor'); // Fetch slot_number and parking_floor
            }
        ])->whereHas('sale', function ($query) {
            $query->whereNotNull('parking_id'); // Ensure parking_id is not null
        })->get();


        // Set additional view data
        $title = 'Return Report All';
        $page = 'sales-return-report';

        // Return view with the data
        return view('salesreturn.returnreportall', compact(
            'building',
            'salesReturns',
            'title',
            'page',
            'salesFlats',
            'parkingDetails',
        ));
    }


    public function commercialreturn($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Commercial Sales Return Report';
        $page = 'commercial-sales-return-report';

        $salesReturns = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'shops') // Filter for room_type = 'shops'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'shops'); // Ensure only rooms of type 'shops' are included
        })->get();

        // Return view with the data 
        return view('salesreturn.commercial', compact(
            'building',
            'title',
            'page',
            'salesReturns'
        ));
    }


    public function apartmentreturn($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Apartment Sales Return Report';
        $page = 'apartment-sales-return-report';

        $salesFlats = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Flats') // Filter for room_type = 'flats'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'flat_build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Flats');
        })->get();

        // Return view with the data
        return view('salesreturn.apartment', compact(
            'building',
            'title',
            'page',
            'salesFlats',
        ));
    }

    public function parkingreturn($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Parking Sales Return Report';
        $page = 'parking-sales-return-report';

        // Fetch parking details
        $parkingDetails = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'parking_id', 'sale_amount', 'customer_name', 'status')
                    ->whereNotNull('parking_id'); // Ensure parking_id is not null
            },
            'sale.parking' => function ($query) {
                $query->select('id', 'slot_number', 'floor_number'); // Fetch slot_number and parking_floor
            }
        ])->whereHas('sale', function ($query) {
            $query->whereNotNull('parking_id'); // Ensure parking_id is not null
        })->get();



        // Return view with the data
        return view('salesreturn.parking', compact(
            'building',
            'title',
            'page',
            'parkingDetails'
        ));
    }
    
}
