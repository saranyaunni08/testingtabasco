<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use App\Models\Parking;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AvailabilityController extends Controller
{
    public function totalavailability($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);
        $parkings = Parking::all();

        // Fetch the availability data (filter by type: shops and flats)
        $availability = Room::where('building_id', $buildingId)
            ->whereIn('room_type', ['Shops', 'Flats']) // Only shops and flats
            ->where('status', 'available') // Filter available shops
            ->select(['room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area', 'flat_build_up_area', 'flat_carpet_area'])
            ->get()
            ->map(function ($room) {
                // Set build_up_area and carpet_area dynamically based on room_type
                $room->build_up_area = $room->room_type === 'Flats' ? $room->flat_build_up_area : $room->build_up_area;
                $room->carpet_area = $room->room_type === 'Flats' ? $room->flat_carpet_area : $room->carpet_area;
                return $room;
            });

        $counterRooms = Room::where('building_id', $buildingId)
            ->where('room_type', 'Counter') // Filter only counter rooms
            ->select(['room_floor', 'custom_type', 'room_number', 'room_type']) // Select required columns
            ->get();

        // Pass the data to the view
        $title = 'Total Availability';
        $page = 'availability-report';

        return view('availability.totalavailability', compact('building', 'availability', 'title', 'page', 'parkings', 'counterRooms'));
    }

    public function availabilityshop($buildingId)
    {
        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);

        // Define the page title and name
        $title = 'Availability Shop';
        $page = 'availability-shop';

        // Query to fetch available shops with the required details
        $availability = Room::where('building_id', $buildingId)
            ->where('room_type', 'Shops') // Filter only shops
            ->where('status', 'available') // Filter available shops
            ->select(['room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area'])
            ->get();

        // Calculate totals for build-up area and carpet area
        $totalBuildUpArea = $availability->sum('build_up_area');
        $totalCarpetArea = $availability->sum('carpet_area');

        // Pass the data to the view
        return view('availability.availabilityshop', compact(
            'building',
            'title',
            'page',
            'availability',
            'totalBuildUpArea',
            'totalCarpetArea'
        ));
    }


    public function availabilityflat($buildingId)
    {
        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);

        // Define the page title and name
        $title = 'Availability Flat';
        $page = 'availability-flat';

        // Fetch the rooms of type 'Flats' and with status 'available'
        $availability = Room::where('building_id', $buildingId)
            ->where('room_type', 'Flats') // Filter only flats
            ->where('status', 'available') // Filter available flats
            ->select(['room_floor', 'room_type', 'room_number', 'flat_build_up_area', 'flat_carpet_area']) // Include the specific fields for flats
            ->get()
            ->map(function ($room) {
                // Set flat_build_up_area as build_up_area and flat_carpet_area as carpet_area
                $room->build_up_area = $room->flat_build_up_area;
                $room->carpet_area = $room->flat_carpet_area;

                // Optionally, remove the flat-specific fields if not needed
                unset($room->flat_build_up_area);
                unset($room->flat_carpet_area);

                return $room;
            });

        // Calculate totals
        $totalBuildUpArea = $availability->sum('build_up_area');
        $totalCarpetArea = $availability->sum('carpet_area');

        // Return the view with the necessary data
        return view('availability.availabilityflat', compact('building', 'title', 'page', 'availability', 'totalBuildUpArea', 'totalCarpetArea'));
    }

    public function availabilityparking($buildingId)
    {

        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);

        // Define the page title and name
        $title = 'Availability Parking';
        $page = 'availability-parking';
        $parkings = Parking::all();

        // Return the view with the necessary data
        return view('availability.availabilityparking', compact('building', 'title', 'page', 'parkings'));
    }

    public function summary($buildingId)
    {
        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);
        // Assuming you have a 'Parking' model
        $parkings = Parking::where('status', 'available') // Filter available parking spots
            ->get();

        // Fetch shops data
        $shops = Room::where('building_id', $buildingId)
            ->where('room_type', 'Shops')
            ->where('status', 'available')
            ->select(['build_up_area', 'carpet_area'])
            ->get();

        // Calculate totals
        $totalBuildUpArea = $shops->sum('build_up_area');
        $totalCarpetArea = $shops->sum('carpet_area');
        $totalShops = $shops->count();

        // Fetch flats data
        $flats = Room::where('building_id', $buildingId)
            ->where('room_type', 'Flats') // Filter flats only
            ->where('status', 'available') // Filter available flats
            ->select(['flat_build_up_area', 'flat_carpet_area'])
            ->get();

        // Calculate totals for flats
        $totalFlatBuildUpArea = $flats->sum('flat_build_up_area');
        $totalFlatCarpetArea = $flats->sum('flat_carpet_area');
        $totalFlats = $flats->count();

        $totalparking = $parkings->count();



        $totalnos = $totalShops + $totalFlats + $totalparking;
        $totalbuildup = $totalBuildUpArea + $totalFlatBuildUpArea;
        $totalcarpet = $totalCarpetArea + $totalFlatCarpetArea;




        // Define the page title and name
        $title = 'Summary';
        $page = 'summary';

        // Pass data to the view
        return view('availability.summary', compact('building', 'title', 'page', 'totalBuildUpArea', 'totalCarpetArea', 'totalShops', 'totalFlats', 'totalFlatCarpetArea', 'totalFlatBuildUpArea', 'totalnos', 'totalbuildup', 'totalcarpet', 'totalparking'));
    }

    public function totalavailabilityPDF($buildingId)
    {

        // Fetch the building details
        $building = Building::findOrFail($buildingId);
        $parkings = Parking::all();

        // Fetch the availability data (filter by type: shops and flats)
        $availability = Room::where('building_id', $buildingId)
            ->whereIn('room_type', ['Shops', 'Flats']) // Only shops and flats
            ->where('status', 'available') // Filter available shops
            ->select(['room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area', 'flat_build_up_area', 'flat_carpet_area'])
            ->get()
            ->map(function ($room) {
                // Set build_up_area and carpet_area dynamically based on room_type
                $room->build_up_area = $room->room_type === 'Flats' ? $room->flat_build_up_area : $room->build_up_area;
                $room->carpet_area = $room->room_type === 'Flats' ? $room->flat_carpet_area : $room->carpet_area;
                return $room;
            });

        $counterRooms = Room::where('building_id', $buildingId)
            ->where('room_type', 'Counter') // Filter only counter rooms
            ->select(['room_floor', 'custom_type', 'room_number', 'room_type']) // Select required columns
            ->get();

        $pdf = PDF::loadView('pdf.total_availability_pdf', compact('building', 'availability', 'parkings', 'counterRooms'));

        return $pdf->download('total_availability.pdf');


    }

    public function availabilityflatPDF($buildingId)
    {

        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);

        // Fetch the rooms of type 'Flats' and with status 'available'
        $availability = Room::where('building_id', $buildingId)
            ->where('room_type', 'Flats') // Filter only flats
            ->where('status', 'available') // Filter available flats
            ->select(['room_floor', 'room_type', 'room_number', 'flat_build_up_area', 'flat_carpet_area']) // Include the specific fields for flats
            ->get()
            ->map(function ($room) {
                // Set flat_build_up_area as build_up_area and flat_carpet_area as carpet_area
                $room->build_up_area = $room->flat_build_up_area;
                $room->carpet_area = $room->flat_carpet_area;

                // Optionally, remove the flat-specific fields if not needed
                unset($room->flat_build_up_area);
                unset($room->flat_carpet_area);

                return $room;
            });

        // Calculate totals
        $totalBuildUpArea = $availability->sum('build_up_area');
        $totalCarpetArea = $availability->sum('carpet_area');

        $pdf = PDF::loadView('pdf.availability_flat_pdf', compact('building', 'availability', 'totalBuildUpArea', 'totalCarpetArea'));

        return $pdf->download('availability_flat.pdf');


    }

    public function availabilityshopPDF($buildingId)
    {

        // Fetch the building details using the given building ID
        $building = Building::findOrFail($buildingId);

        // Query to fetch available shops with the required details
        $availability = Room::where('building_id', $buildingId)
            ->where('room_type', 'Shops') // Filter only shops
            ->where('status', 'available') // Filter available shops
            ->select(['room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area'])
            ->get();

        // Calculate totals for build-up area and carpet area
        $totalBuildUpArea = $availability->sum('build_up_area');
        $totalCarpetArea = $availability->sum('carpet_area');

        
        $pdf = PDF::loadView('pdf.availability_shop_pdf', compact('building', 'availability',
            'totalBuildUpArea',
            'totalCarpetArea'));

        return $pdf->download('availability_shop.pdf');





    }







}
