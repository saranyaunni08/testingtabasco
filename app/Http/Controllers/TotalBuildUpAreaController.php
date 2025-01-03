<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;
use App\Models\Sale;
use App\Models\Parking;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class TotalBuildUpAreaController extends Controller
{
    public function totalbuildup($building_id)
    {
        $sale = Sale::whereHas('room', function ($query) {
            $query->where('building_id');
        })->first();
        $title = 'Total Breakup';
        $page = 'total-build-up-area-details';
        $building = Building::findOrFail($building_id);
        $apartments = $building->rooms()->where('room_type', 'Flats')->get();

        $commercialspaces = $building->rooms()
            ->whereIn('room_type', ['Shops'])
            ->get();
        // Fetch parking spaces and eager load sales data
        $parkings = Parking::with('sales')->get();

        $rooms = Room::where('building_id', $building->id)
            ->select(
                'id',
                'room_floor',
                'room_type',
                'room_number',
                'flat_build_up_area',
                'build_up_area',
                'space_area',
                'kiosk_area',
                'chair_space_in_sq',
                'custom_area',
                'flat_carpet_area',
                'carpet_area',
                'flat_super_build_up_price',
                'super_build_up_price',
                'kiosk_rate',
                'chair_space_rate',
                'space_rate',
                'custom_rate',
                'expected_amount',
                'total_amount', // Directly fetching total_amount from rooms table
                'sale_amount', // Directly fetching sale_amount from rooms table
                'status'
            )
            ->get()
            ->map(function ($room) {
                // Determine the correct build-up area field based on room type
                $build_up_area = match ($room->room_type) {
                    'Flat' => $room->flat_build_up_area,
                    'Shop' => $room->build_up_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                $carpet_area = match ($room->room_type) {
                    'Flat' => $room->flat_carpet_area,
                    'Shop' => $room->carpet_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($room->room_type) {
                    'Flat' => $room->flat_super_build_up_price,
                    'Shop' => $room->super_build_up_price,
                    'Table space' => $room->space_rate,
                    'Kiosk' => $room->kiosk_rate,
                    'Chair space' => $room->chair_space_rate,
                    default => $room->custome_rate,
                };

                return [
                    'room_floor' => $room->room_floor,
                    'room_type' => $room->room_type,
                    'room_number' => $room->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $room->total_amount, // Fetching total_amount directly
                    'expected_amount' => $room->expected_amount ?? 0,
                    'difference' => $room->total_amount - ($room->expected_amount ?? 0),
                    'status' => $room->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $room->sale_amount, // Fetching sale_amount directly
                ];
            });



        // Fetch Commercial Data
        $spaces = Room::with('sales')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'space_area', 'kiosk_area', 'chair_space_in_sq', 'custom_area', 'carpet_area', 'expected_amount', 'flat_super_build_up_price', 'super_build_up_price', 'kiosk_rate', 'chair_space_rate', 'space_rate', 'custom_rate', 'expected_amount', 'total_amount', 'sale_amount', 'status')
            ->get()
            ->map(function ($space) {
                $build_up_area = match ($space->room_type) {
                    'Shop' => $space->build_up_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                $carpet_area = match ($space->room_type) {
                    'Shop' => $space->carpet_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($space->room_type) {
                    'Flat' => $space->flat_super_build_up_price,
                    'Shop' => $space->super_build_up_price,
                    'Table space' => $space->space_rate,
                    'Kiosk' => $space->kiosk_rate,
                    'Chair space' => $space->chair_space_rate,
                    default => $space->custome_rate,
                };


                return [
                    'room_floor' => $space->room_floor,
                    'room_type' => $space->room_type,
                    'room_number' => $space->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $space->total_amount, // Fetching total_amount directly
                    'expected_amount' => $space->expected_amount ?? 0,
                    'difference' => $space->total_amount - ($space->expected_amount ?? 0),
                    'status' => $space->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $space->sale_amount, // Fetching sale_amount directly
    


                ];
            });

        // Totals
        $totalBuildUpArea = $rooms->sum('build_up_area') + $spaces->sum('build_up_area');
        $totalCarpetArea = $rooms->sum('carpet_area') + $spaces->sum('carpet_area');
        $totalExpectedAmount = $rooms->sum('expected_amount') + $spaces->sum('expected_amount');
        $totalSaleAmount = $rooms->sum('sale_amount') + $spaces->sum('sale_amount');
        $totalDifference = $rooms->sum('difference') + $spaces->sum('difference');

        return view('totalbuildupexcel.total_breakup', compact(
            'title',
            'page',
            'rooms',
            'spaces',
            'totalBuildUpArea',
            'totalCarpetArea',
            'totalExpectedAmount',
            'totalSaleAmount',
            'totalDifference',
            'building',
            'sale',
            'apartments',
            'commercialspaces',
            'parkings',

        ));
    }

    public function index($building_id)
    {
        $title = 'Apartment Breakup';
        $page = 'apartmentbreakup';

        // Fetch the building using the building_id
        $building = Building::findOrFail($building_id);


        $apartments = $building->rooms()->where('room_type', 'Flats')->get();
        // Fetch rooms associated with this building
        $rooms = Room::where('building_id', $building->id)
            ->select(
                'id',
                'room_floor',
                'room_type',
                'room_number',
                'flat_build_up_area',
                'build_up_area',
                'space_area',
                'kiosk_area',
                'chair_space_in_sq',
                'custom_area',
                'flat_carpet_area',
                'carpet_area',
                'flat_super_build_up_price',
                'super_build_up_price',
                'kiosk_rate',
                'chair_space_rate',
                'space_rate',
                'custom_rate',
                'expected_amount',
                'total_amount', // Directly fetching total_amount from rooms table
                'sale_amount', // Directly fetching sale_amount from rooms table
                'status'
            )
            ->get()
            ->map(function ($room) {
                // Determine the correct build-up area field based on room type
                $build_up_area = match ($room->room_type) {
                    'Flat' => $room->flat_build_up_area,
                    'Shop' => $room->build_up_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                $carpet_area = match ($room->room_type) {
                    'Flat' => $room->flat_carpet_area,
                    'Shop' => $room->carpet_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($room->room_type) {
                    'Flat' => $room->flat_super_build_up_price,
                    'Shop' => $room->super_build_up_price,
                    'Table space' => $room->space_rate,
                    'Kiosk' => $room->kiosk_rate,
                    'Chair space' => $room->chair_space_rate,
                    default => $room->custome_rate,
                };

                return [
                    'room_floor' => $room->room_floor,
                    'room_type' => $room->room_type,
                    'room_number' => $room->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $room->total_amount, // Fetching total_amount directly
                    'expected_amount' => $room->expected_amount ?? 0,
                    'difference' => $room->total_amount - ($room->expected_amount ?? 0),
                    'status' => $room->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $room->sale_amount, // Fetching sale_amount directly
                ];
            });
        // Calculate totals
        $totalBuildUpArea = $apartments->sum('build_up_area');
        $totalCarpetArea = $apartments->sum('carpet_area');
        $totalExpectedAmount = $apartments->sum('expected_amount');
        $totalSaleAmount = $apartments->sum('sale_amount');
        $totalDifference = $apartments->sum('difference');

        // Return the view with the necessary data
        return view('totalbuildupexcel.apartment_breakup', compact(
            'title',
            'page',
            'apartments',
            'building',
            'totalBuildUpArea',
            'totalCarpetArea',
            'totalExpectedAmount',
            'totalSaleAmount',
            'totalDifference',
        ));
    }



    public function commercialbreakup($building_id)
    {
        $title = 'Commercial Breakup';
        $page = 'commercialbreakup';

        // Fetch the building using the building_id
        $building = Building::findOrFail($building_id);
        $commercialspaces = $building->rooms()
            ->whereIn('room_type', ['Shops'])
            ->get();

        // Fetch Commercial Data
        $spaces = Room::with('sales')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'space_area', 'kiosk_area', 'chair_space_in_sq', 'custom_area', 'carpet_area', 'expected_amount', 'flat_super_build_up_price', 'super_build_up_price', 'kiosk_rate', 'chair_space_rate', 'space_rate', 'custom_rate', 'expected_amount', 'total_amount', 'sale_amount', 'status')
            ->get()
            ->map(function ($space) {
                $build_up_area = match ($space->room_type) {
                    'Shop' => $space->build_up_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                $carpet_area = match ($space->room_type) {
                    'Shop' => $space->carpet_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($space->room_type) {
                    'Flat' => $space->flat_super_build_up_price,
                    'Shop' => $space->super_build_up_price,
                    'Table space' => $space->space_rate,
                    'Kiosk' => $space->kiosk_rate,
                    'Chair space' => $space->chair_space_rate,
                    default => $space->custome_rate,
                };


                return [
                    'room_floor' => $space->room_floor,
                    'room_type' => $space->room_type,
                    'room_number' => $space->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $space->total_amount, // Fetching total_amount directly
                    'expected_amount' => $space->expected_amount ?? 0,
                    'difference' => $space->total_amount - ($space->expected_amount ?? 0),
                    'status' => $space->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $space->sale_amount, // Fetching sale_amount directly
    


                ];
            });



        // Calculate totals for commercial spaces
        $totalBuildUpArea = $commercialspaces->sum('build_up_area');
        $totalCarpetArea = $commercialspaces->sum('carpet_area');
        $totalExpectedAmount = $commercialspaces->sum('expected_amount');
        $totalSaleAmount = $commercialspaces->sum('sale_amount');
        $totalDifference = $commercialspaces->sum('difference');

        return view('totalbuildupexcel.commercial_breakup', compact('commercialspaces', 'totalBuildUpArea', 'totalCarpetArea', 'totalExpectedAmount', 'totalSaleAmount', 'totalDifference', 'title', 'page', 'building'));

    }

    public function parkingbreakup($buildingId)
    {
        $title = 'Parking Breakup';
        $page = 'parkingbreakup';
        // Logic for handling the parking breakup
        $building = Building::findOrFail($buildingId);
        // Perform the necessary data fetching or calculations
        // Fetch parking spaces and eager load sales data
        $parkings = Parking::with('sales')->get();

        // Calculate the totals
        $totalExpectedSale = $parkings->sum('expected_amount');
        $totalSaleAmount = $parkings->sum('sale_amount');
        $totalDifference = $parkings->sum('difference');

        return view('totalbuildupexcel.parking_breakup', compact('building', 'title', 'page', 'parkings', 'totalExpectedSale', 'totalSaleAmount', 'totalDifference'));
    }


    public function summary($buildingId)
    {
        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Calculate the total square feet for all rooms
        $totalSqFt = $building->rooms->sum('flat_build_up_area');

        // Calculate the total square feet sold
        $totalSqFtSold = $building->rooms
            ->where('status', 'sold') // Filter sold rooms
            ->sum('flat_build_up_area');

        // Calculate the balance square feet
        $balanceSqFt = $totalSqFt - $totalSqFtSold;


        // Calculate the total commercial total square feet
        $commercialTotalSqft = $building->rooms->sum('build_up_area');

        //Calculate the total commercial total square feet sold 
        $commercialTotalSqftSold = $building->rooms
            ->where('status', 'sold') // Filter sold rooms
            ->sum('build_up_area');

        $commercialBalanceSqft = $commercialTotalSqft - $commercialTotalSqftSold;
        // Filter only apartments from rooms
        $apartments = $building->rooms->where('type', 'Flats');

        // Calculate the total expected amount for apartments
        $totalExpectedAmount = $apartments->sum('expected_amount');
        // Fetch parking data related to the building
        $parkings = Parking::all();
        // Calculate the totals
        $ParkingtotalExpectedSale = $parkings->sum('amount');
        // calculate the totalexpected amount for commercial spaces
        $commercialspaces = $building->rooms->where('type', 'Shops', 'Tablespaces', 'Kiosks', 'Chairspaces');
        $totalcommercialExpectedAmount = $commercialspaces->sum('expected_amount');

        // calculate the totalexpected sale 

        // Calculate the total expected sale by summing the amounts
        $totalExpectedSale = $totalExpectedAmount + $ParkingtotalExpectedSale + $totalcommercialExpectedAmount;

        // Calculate the total sale amount for apartments sold
        $apartmentsSold = $apartments->where('status', 'sold')->sum('sale_amount');
        // Calculate the total sale amount for parking sold
        $parkingSold = $parkings->where('status', 'sold')->sum('sale_amount');
        // Calculate the total sale amount for commercial spaces sold
        $commercialSold = $commercialspaces->where('status', 'sold')->sum('sale_amount');
        // Calculate the total sale amount for all sold properties (apartments, parking, and commercial)
        $totalSold = $apartmentsSold + $parkingSold + $commercialSold;


        // Pass the data to the view
        $title = 'Summary';
        $page = 'summarypage';

        // Correct compact usage by removing extra spaces
        return view('totalbuildupexcel.summary', compact(
            'building',
            'title',
            'page',
            'totalSqFt',
            'totalSqFtSold',
            'balanceSqFt',
            'commercialTotalSqft',
            'commercialTotalSqftSold',
            'commercialBalanceSqft',
            'totalExpectedAmount',
            'ParkingtotalExpectedSale',
            'totalcommercialExpectedAmount',
            'totalExpectedSale',
            'apartmentsSold',
            'parkingSold',
            'commercialSold',
            'totalSold'
        ));
    }



    public function balancesummary($buildingId)
    {
        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Fetch all parking data from the parkings table
        $parkings = Parking::all();

        // Get the total count of parking entries
        $totalParkingCount = $parkings->count();
        $soldParkingCount = $parkings->where('status', 'occupied')->count();
        $availableParkingCount = $parkings->where('status', 'available')->count();
        $expectedBalanceAmountParking = $parkings->sum('amount');

        // Parking Sale Amount calculation from the sales table using parking_id
        $saleAmountParking = $parkings->where('status', 'occupied')->map(function ($parking) {
            return Sale::where('parking_id', $parking->id)
                ->sum(DB::raw('parking_amount_cash + parking_amount_cheque'));
        })->sum();

        // New variable for storing parking data
        $parkingData = [
            'type' => 'Parking',
            'totalSqFt' => $totalParkingCount,
            'salesSqFt' => $soldParkingCount,
            'saleAmount' => $saleAmountParking,
            'balanceSqFt' => $availableParkingCount,
            'expectedBalanceAmount' => $expectedBalanceAmountParking,
        ];

        // Now, calculate room type data for 'Flats', 'Shops', 'Chairspaces', and 'Kiosk'
        $rooms = $building->rooms;
        $roomTypeData = [];

        // Calculate Flats data
        $flats = $rooms->where('room_type', 'Flats');

        $totalFlatSqFt = $flats->sum('flat_build_up_area');
        $soldFlatSqFt = $flats->where('status', 'sold')->sum('flat_build_up_area');
        $soldFlatAmount = $flats->where('status', 'sold')->sum('total_amount');
        $availableFlatSqFt = $flats->where('status', 'available')->sum('flat_build_up_area');
        $expectedBalanceAmountFlats = $flats->where('status', 'available')->sum('expected_amount');

        // Storing the data for Flats room type
        $roomTypeData[] = [
            'type' => 'Flats',
            'totalSqFt' => $totalFlatSqFt,
            'salesSqFt' => $soldFlatSqFt,
            'saleAmount' => $soldFlatAmount,
            'balanceSqFt' => $availableFlatSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountFlats,
        ];

        // Calculate Shops data
        $shops = $rooms->where('room_type', 'Shops');

        $totalShopSqFt = $shops->sum('build_up_area');
        $soldShopSqFt = $shops->where('status', 'sold')->sum('build_up_area');
        $soldShopAmount = $shops->where('status', 'sold')->sum('total_amount');
        $availableShopSqFt = $shops->where('status', 'available')->sum('build_up_area');
        $expectedBalanceAmountShops = $shops->where('status', 'available')->sum('expected_amount');

        // Storing the data for Shops room type
        $roomTypeData[] = [
            'type' => 'Shops',
            'totalSqFt' => $totalShopSqFt,
            'salesSqFt' => $soldShopSqFt,
            'saleAmount' => $soldShopAmount,
            'balanceSqFt' => $availableShopSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountShops,
        ];

        // Calculate Chairspaces data (use 'chair_space_in_sq' instead of 'space_area')
        $chairspaces = $rooms->where('room_type', 'Chairspaces');

        $totalChairspaceSqFt = $chairspaces->sum('chair_space_in_sq'); // Adjusted field name
        $soldChairspaceSqFt = $chairspaces->where('status', 'sold')->sum('chair_space_in_sq'); // Adjusted field name
        $soldChairspaceAmount = $chairspaces->where('status', 'sold')->sum('total_amount');
        $availableChairspaceSqFt = $chairspaces->where('status', 'available')->sum('chair_space_in_sq'); // Adjusted field name
        $expectedBalanceAmountChairspaces = $chairspaces->where('status', 'available')->sum('expected_amount');

        // Storing the data for Chairspaces room type
        $roomTypeData[] = [
            'type' => 'Chairspaces',
            'totalSqFt' => $totalChairspaceSqFt,
            'salesSqFt' => $soldChairspaceSqFt,
            'saleAmount' => $soldChairspaceAmount,
            'balanceSqFt' => $availableChairspaceSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountChairspaces,
        ];

        // Calculate Kiosk data
        $kiosks = $rooms->where('room_type', 'Kiosks');

        $totalKioskSqFt = $kiosks->sum('kiosk_area');  // Assuming 'kiosk_area' is the field for kiosks
        $soldKioskSqFt = $kiosks->where('status', 'sold')->sum('kiosk_area');
        $soldKioskAmount = $kiosks->where('status', 'sold')->sum('total_amount');
        $availableKioskSqFt = $kiosks->where('status', 'available')->sum('kiosk_area');
        $expectedBalanceAmountKiosks = $kiosks->where('status', 'available')->sum('expected_amount');

        // Storing the data for Kiosk room type
        $roomTypeData[] = [
            'type' => 'Kiosk',
            'totalSqFt' => $totalKioskSqFt,
            'salesSqFt' => $soldKioskSqFt,
            'saleAmount' => $soldKioskAmount,
            'balanceSqFt' => $availableKioskSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountKiosks,
        ];

        // Calculate TableSpace data
        $tablespaces = $rooms->where('room_type', 'Tablespaces');  // Assuming 'TableSpace' is the room type name

        $totalTableSpaceSqFt = $tablespaces->sum('space_area');  // Assuming 'space_area' is the field for tablespace
        $soldTableSpaceSqFt = $tablespaces->where('status', 'sold')->sum('space_area');
        $soldTableSpaceAmount = $tablespaces->where('status', 'sold')->sum('total_amount');
        $availableTableSpaceSqFt = $tablespaces->where('status', 'available')->sum('space_area');
        $expectedBalanceAmountTableSpaces = $tablespaces->where('status', 'available')->sum('expected_amount');


        // Storing the data for TableSpace room type
        $roomTypeData[] = [
            'type' => 'Tablespace',
            'totalSqFt' => $totalTableSpaceSqFt,
            'salesSqFt' => $soldTableSpaceSqFt,
            'saleAmount' => $soldTableSpaceAmount,
            'balanceSqFt' => $availableTableSpaceSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountTableSpaces,
        ];


        // Handle other room types that are not Flats, Shops, TableSpaces, ChairSpaces, or Kiosks
        $otherRooms = $rooms->whereNotIn('room_type', ['Flats', 'Shops', 'Tablespaces', 'Chairspaces', 'Kiosks']);

        foreach ($otherRooms as $room) {
            // Summing data for other room types (assuming 'space_area' is used for all other room types)
            $totalOtherRoomSqFt = $room->custom_area; // Adjust based on the column name used for area
            $soldOtherRoomSqFt = $room->status == 'sold' ? $room->custom_area : 0;
            $soldOtherRoomAmount = $room->status == 'sold' ? $room->total_amount : 0;
            $availableOtherRoomSqFt = $room->status == 'available' ? $room->custom_area : 0;
            $expectedBalanceAmountOtherRooms = $room->status == 'available' ? $room->expected_amount : 0;

            // Storing the data for other room types
            $roomTypeData[] = [
                'type' => $room->room_type,
                'totalSqFt' => $totalOtherRoomSqFt,
                'salesSqFt' => $soldOtherRoomSqFt,
                'saleAmount' => $soldOtherRoomAmount,
                'balanceSqFt' => $availableOtherRoomSqFt,
                'expectedBalanceAmount' => $expectedBalanceAmountOtherRooms,
            ];
        }

        // Pass the calculated data to the view
        $title = 'Balance Summary';
        $page = 'balancesummary';

        // Return the view with room type data and parking data
        return view('totalbuildupexcel.balance_summary', compact('building', 'title', 'page', 'parkingData', 'roomTypeData'));
    }



    public function changesinExpectedamount($buildingId)
    {
        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Fetch rooms data for the specific buildingId with room_type as 'shop'
        $rooms = DB::table('rooms')
            ->where('building_id', $buildingId)
            ->where('room_type', 'Shops')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area', 'expected_amount')
            ->get();

        // Fetch sales data for the room_ids fetched from the rooms table
        $roomIds = $rooms->pluck('id'); // Corrected to pluck 'id' instead of 'room_id'
        $sales = DB::table('sales')
            ->whereIn('room_id', $roomIds)
            ->select('room_id', 'created_at', 'sale_amount')
            ->get();

        // Merge room and sales data
        $mergedData = $rooms->map(function ($room) use ($sales) {
            $sale = $sales->firstWhere('room_id', $room->id); // Corrected to use 'id'
            $room->created_at = $sale->created_at ?? null; // Add created_at if found
            $room->sale_amount = $sale->sale_amount ?? null; // Add sale_amount if found
            return $room;
        });

        // Group the merged data by room_floor
        $groupedData = $mergedData->groupBy('room_floor');

        // Set additional page data
        $title = 'Changes In Expected Amount';
        $page = 'changesinexpectedamount';

        // Return the view with the grouped data
        return view('totalbuildupexcel.changes_in_expected_amount', compact('groupedData', 'title', 'page', 'building'));
    }


    public function totalbuildupPDF($buildingId)
    {
        $sale = Sale::whereHas('room', function ($query) {
            $query->where('building_id');
        })->first();
        $title = 'Total Breakup';
        $page = 'total-build-up-area-details';
        $building = Building::with('rooms')->findOrFail($buildingId);
        $apartments = $building->rooms()->where('room_type', 'Flats')->get();

        $commercialspaces = $building->rooms()
            ->whereIn('room_type', ['Shops'])
            ->get();
        // Fetch parking spaces and eager load sales data
        $parkings = Parking::with('sales')->get();

        $rooms = Room::where('building_id', $building->id)
            ->select(
                'id',
                'room_floor',
                'room_type',
                'room_number',
                'flat_build_up_area',
                'build_up_area',
                'space_area',
                'kiosk_area',
                'chair_space_in_sq',
                'custom_area',
                'flat_carpet_area',
                'carpet_area',
                'flat_super_build_up_price',
                'super_build_up_price',
                'kiosk_rate',
                'chair_space_rate',
                'space_rate',
                'custom_rate',
                'expected_amount',
                'total_amount', // Directly fetching total_amount from rooms table
                'sale_amount', // Directly fetching sale_amount from rooms table
                'status'
            )
            ->get()
            ->map(function ($room) {
                // Determine the correct build-up area field based on room type
                $build_up_area = match ($room->room_type) {
                    'Flat' => $room->flat_build_up_area,
                    'Shop' => $room->build_up_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                $carpet_area = match ($room->room_type) {
                    'Flat' => $room->flat_carpet_area,
                    'Shop' => $room->carpet_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($room->room_type) {
                    'Flat' => $room->flat_super_build_up_price,
                    'Shop' => $room->super_build_up_price,
                    'Table space' => $room->space_rate,
                    'Kiosk' => $room->kiosk_rate,
                    'Chair space' => $room->chair_space_rate,
                    default => $room->custome_rate,
                };

                return [
                    'room_floor' => $room->room_floor,
                    'room_type' => $room->room_type,
                    'room_number' => $room->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $room->total_amount, // Fetching total_amount directly
                    'expected_amount' => $room->expected_amount ?? 0,
                    'difference' => $room->total_amount - ($room->expected_amount ?? 0),
                    'status' => $room->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $room->sale_amount, // Fetching sale_amount directly
                ];
            });



        // Fetch Commercial Data
        $spaces = Room::with('sales')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'space_area', 'kiosk_area', 'chair_space_in_sq', 'custom_area', 'carpet_area', 'expected_amount', 'flat_super_build_up_price', 'super_build_up_price', 'kiosk_rate', 'chair_space_rate', 'space_rate', 'custom_rate', 'expected_amount', 'total_amount', 'sale_amount', 'status')
            ->get()
            ->map(function ($space) {
                $build_up_area = match ($space->room_type) {
                    'Shop' => $space->build_up_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                $carpet_area = match ($space->room_type) {
                    'Shop' => $space->carpet_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($space->room_type) {
                    'Flat' => $space->flat_super_build_up_price,
                    'Shop' => $space->super_build_up_price,
                    'Table space' => $space->space_rate,
                    'Kiosk' => $space->kiosk_rate,
                    'Chair space' => $space->chair_space_rate,
                    default => $space->custome_rate,
                };


                return [
                    'room_floor' => $space->room_floor,
                    'room_type' => $space->room_type,
                    'room_number' => $space->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $space->total_amount, // Fetching total_amount directly
                    'expected_amount' => $space->expected_amount ?? 0,
                    'difference' => $space->total_amount - ($space->expected_amount ?? 0),
                    'status' => $space->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $space->sale_amount, // Fetching sale_amount directly
    


                ];
            });

        // Totals
        $totalBuildUpArea = $rooms->sum('build_up_area') + $spaces->sum('build_up_area');
        $totalCarpetArea = $rooms->sum('carpet_area') + $spaces->sum('carpet_area');
        $totalExpectedAmount = $rooms->sum('expected_amount') + $spaces->sum('expected_amount');
        $totalSaleAmount = $rooms->sum('sale_amount') + $spaces->sum('sale_amount');
        $totalDifference = $rooms->sum('difference') + $spaces->sum('difference');


        $pdf = PDF::loadView('pdf.total_breakup_pdf', compact(
            'building',
            'rooms',
            'spaces',
            'totalBuildUpArea',
            'totalCarpetArea',
            'totalExpectedAmount',
            'totalSaleAmount',
            'totalDifference',
            'building',
            'sale',
            'apartments',
            'commercialspaces',
            'parkings',
        ));

        // Return the generated PDF
        return $pdf->download('total_breakup.pdf');

    }

    public function apartmentbreakupPDF($buildingId)
    {
        // Fetch the building using the building_id
        $building = Building::findOrFail($buildingId);


        $apartments = $building->rooms()->where('room_type', 'Flats')->get();
        // Fetch rooms associated with this building
        $rooms = Room::where('building_id', $building->id)
            ->select(
                'id',
                'room_floor',
                'room_type',
                'room_number',
                'flat_build_up_area',
                'build_up_area',
                'space_area',
                'kiosk_area',
                'chair_space_in_sq',
                'custom_area',
                'flat_carpet_area',
                'carpet_area',
                'flat_super_build_up_price',
                'super_build_up_price',
                'kiosk_rate',
                'chair_space_rate',
                'space_rate',
                'custom_rate',
                'expected_amount',
                'total_amount', // Directly fetching total_amount from rooms table
                'sale_amount', // Directly fetching sale_amount from rooms table
                'status'
            )
            ->get()
            ->map(function ($room) {
                // Determine the correct build-up area field based on room type
                $build_up_area = match ($room->room_type) {
                    'Flat' => $room->flat_build_up_area,
                    'Shop' => $room->build_up_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                $carpet_area = match ($room->room_type) {
                    'Flat' => $room->flat_carpet_area,
                    'Shop' => $room->carpet_area,
                    'Table space' => $room->space_area,
                    'Kiosk' => $room->kiosk_area,
                    'Chair space' => $room->chair_space_in_sq,
                    default => $room->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($room->room_type) {
                    'Flat' => $room->flat_super_build_up_price,
                    'Shop' => $room->super_build_up_price,
                    'Table space' => $room->space_rate,
                    'Kiosk' => $room->kiosk_rate,
                    'Chair space' => $room->chair_space_rate,
                    default => $room->custome_rate,
                };

                return [
                    'room_floor' => $room->room_floor,
                    'room_type' => $room->room_type,
                    'room_number' => $room->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $room->total_amount, // Fetching total_amount directly
                    'expected_amount' => $room->expected_amount ?? 0,
                    'difference' => $room->total_amount - ($room->expected_amount ?? 0),
                    'status' => $room->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $room->sale_amount, // Fetching sale_amount directly
                ];
            });
        // Calculate totals
        $totalBuildUpArea = $apartments->sum('build_up_area');
        $totalCarpetArea = $apartments->sum('carpet_area');
        $totalExpectedAmount = $apartments->sum('expected_amount');
        $totalSaleAmount = $apartments->sum('sale_amount');
        $totalDifference = $apartments->sum('difference');


        $pdf = PDF::loadView('pdf.apartment_break_up_pdf', compact(
            'apartments',
            'building',
            'totalBuildUpArea',
            'totalCarpetArea',
            'totalExpectedAmount',
            'totalSaleAmount',
            'totalDifference'
        ));


        // Return the generated PDF
        return $pdf->download('apartment_break_up.pdf');


    }

    public function commercialbreakupPDF($buildingId)
    {

        // Fetch the building using the building_id
        $building = Building::findOrFail($buildingId);
        $commercialspaces = $building->rooms()
            ->whereIn('room_type', ['Shops'])
            ->get();

        // Fetch Commercial Data
        $spaces = Room::with('sales')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'space_area', 'kiosk_area', 'chair_space_in_sq', 'custom_area', 'carpet_area', 'expected_amount', 'flat_super_build_up_price', 'super_build_up_price', 'kiosk_rate', 'chair_space_rate', 'space_rate', 'custom_rate', 'expected_amount', 'total_amount', 'sale_amount', 'status')
            ->get()
            ->map(function ($space) {
                $build_up_area = match ($space->room_type) {
                    'Shop' => $space->build_up_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                $carpet_area = match ($space->room_type) {
                    'Shop' => $space->carpet_area,
                    'Table space' => $space->space_area,
                    'Kiosk' => $space->kiosk_area,
                    'Chair space' => $space->chair_space_in_sq,
                    default => $space->custom_area,
                };

                // Determine the correct expected per square foot rate based on room type
                $expected_rate = match ($space->room_type) {
                    'Flat' => $space->flat_super_build_up_price,
                    'Shop' => $space->super_build_up_price,
                    'Table space' => $space->space_rate,
                    'Kiosk' => $space->kiosk_rate,
                    'Chair space' => $space->chair_space_rate,
                    default => $space->custome_rate,
                };


                return [
                    'room_floor' => $space->room_floor,
                    'room_type' => $space->room_type,
                    'room_number' => $space->room_number,
                    'build_up_area' => $build_up_area,
                    'carpet_area' => $carpet_area,
                    'sale_amount' => $space->total_amount, // Fetching total_amount directly
                    'expected_amount' => $space->expected_amount ?? 0,
                    'difference' => $space->total_amount - ($space->expected_amount ?? 0),
                    'status' => $space->status,
                    'expected_per_sqft' => $expected_rate,
                    'sale_per_sqft' => $space->sale_amount, // Fetching sale_amount directly
    


                ];
            });



        // Calculate totals for commercial spaces
        $totalBuildUpArea = $commercialspaces->sum('build_up_area');
        $totalCarpetArea = $commercialspaces->sum('carpet_area');
        $totalExpectedAmount = $commercialspaces->sum('expected_amount');
        $totalSaleAmount = $commercialspaces->sum('sale_amount');
        $totalDifference = $commercialspaces->sum('difference');

        $pdf = PDF::loadView('pdf.commercial_break_up_pdf', compact('commercialspaces', 'totalBuildUpArea', 'totalCarpetArea', 'totalExpectedAmount', 'totalSaleAmount', 'totalDifference', 'building'));

        return $pdf->download('commercial_break_up.pdf');


    }

    public function parkingbreakupPDF($buildingId)
    {
        // Logic for handling the parking breakup
        $building = Building::findOrFail($buildingId);
        // Perform the necessary data fetching or calculations
        // Fetch parking spaces and eager load sales data
        $parkings = Parking::with('sales')->get();

        // Calculate the totals
        $totalExpectedSale = $parkings->sum('expected_amount');
        $totalSaleAmount = $parkings->sum('sale_amount');
        $totalDifference = $parkings->sum('difference');

        $pdf = PDF::loadView('pdf.parking_break_up_pdf', compact('parkings', 'totalExpectedSale', 'totalSaleAmount', 'totalDifference', 'building'));

        return $pdf->download('parking_break_up.pdf');


    }

    public function summarybreakupPDF($buildingId)
    {

        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Calculate the total square feet for all rooms
        $totalSqFt = $building->rooms->sum('flat_build_up_area');

        // Calculate the total square feet sold
        $totalSqFtSold = $building->rooms
            ->where('status', 'SOLD') // Filter sold rooms
            ->sum('flat_build_up_area');

        // Calculate the balance square feet
        $balanceSqFt = $totalSqFt - $totalSqFtSold;


        // Calculate the total commercial total square feet
        $commercialTotalSqft = $building->rooms->sum('build_up_area');

        //Calculate the total commercial total square feet sold 
        $commercialTotalSqftSold = $building->rooms
            ->where('status', 'SOLD') // Filter sold rooms
            ->sum('build_up_area');

        $commercialBalanceSqft = $commercialTotalSqft - $commercialTotalSqftSold;
        // Filter only apartments from rooms
        $apartments = $building->rooms->where('type', 'Flats');

        // Calculate the total expected amount for apartments
        $totalExpectedAmount = $apartments->sum('expected_amount');
        // Fetch parking data related to the building
        $parkings = Parking::all();
        // Calculate the totals
        $ParkingtotalExpectedSale = $parkings->sum('amount');
        // calculate the totalexpected amount for commercial spaces
        $commercialspaces = $building->rooms->where('type', 'Shops', 'Tablespaces', 'Kiosks', 'Chairspaces');
        $totalcommercialExpectedAmount = $commercialspaces->sum('expected_amount');

        // calculate the totalexpected sale 

        // Calculate the total expected sale by summing the amounts
        $totalExpectedSale = $totalExpectedAmount + $ParkingtotalExpectedSale + $totalcommercialExpectedAmount;

        // Calculate the total sale amount for apartments sold
        $apartmentsSold = $apartments->where('status', 'SOLD')->sum('sale_amount');
        // Calculate the total sale amount for parking sold
        $parkingSold = $parkings->where('status', 'SOLD')->sum('sale_amount');
        // Calculate the total sale amount for commercial spaces sold
        $commercialSold = $commercialspaces->where('status', 'SOLD')->sum('sale_amount');
        // Calculate the total sale amount for all sold properties (apartments, parking, and commercial)
        $totalSold = $apartmentsSold + $parkingSold + $commercialSold;


        $pdf = PDF::loadView('pdf.summary_break_up_pdf', compact(
            'totalSqFt',
            'totalSqFtSold',
            'balanceSqFt',
            'commercialTotalSqft',
            'commercialTotalSqftSold',
            'commercialBalanceSqft',
            'totalExpectedAmount',
            'ParkingtotalExpectedSale',
            'totalcommercialExpectedAmount',
            'totalExpectedSale',
            'apartmentsSold',
            'parkingSold',
            'commercialSold',
            'totalSold',
            'building'
        ));

        return $pdf->download('summary_break_up.pdf');
    }

    public function changesinexpectedamountPDF($buildingId)
    {

        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Fetch rooms data for the specific buildingId with room_type as 'shop'
        $rooms = DB::table('rooms')
            ->where('building_id', $buildingId)
            ->where('room_type', 'Shops')
            ->select('id', 'room_floor', 'room_type', 'room_number', 'build_up_area', 'carpet_area', 'expected_amount')
            ->get();

        // Fetch sales data for the room_ids fetched from the rooms table
        $roomIds = $rooms->pluck('id'); // Corrected to pluck 'id' instead of 'room_id'
        $sales = DB::table('sales')
            ->whereIn('room_id', $roomIds)
            ->select('room_id', 'created_at', 'sale_amount')
            ->get();

        // Merge room and sales data
        $mergedData = $rooms->map(function ($room) use ($sales) {
            $sale = $sales->firstWhere('room_id', $room->id); // Corrected to use 'id'
            $room->created_at = $sale->created_at ?? null; // Add created_at if found
            $room->sale_amount = $sale->sale_amount ?? null; // Add sale_amount if found
            return $room;
        });

        // Group the merged data by room_floor
        $groupedData = $mergedData->groupBy('room_floor');

        $pdf = PDF::loadView('pdf.changes_in_expected_amount_pdf', compact('groupedData', 'building'));

        return $pdf->download('changes_in_expected_amount.pdf');
    }

    public function balancesummaryPDF($buildingId)
    {

        // Fetch the building and related rooms
        $building = Building::with('rooms')->findOrFail($buildingId);

        // Fetch all parking data from the parkings table
        $parkings = Parking::all();

        // Get the total count of parking entries
        $totalParkingCount = $parkings->count();
        $soldParkingCount = $parkings->where('status', 'occupied')->count();
        $availableParkingCount = $parkings->where('status', 'available')->count();
        $expectedBalanceAmountParking = $parkings->sum('amount');

        // Parking Sale Amount calculation from the sales table using parking_id
        $saleAmountParking = $parkings->where('status', 'occupied')->map(function ($parking) {
            return Sale::where('parking_id', $parking->id)
                ->sum(DB::raw('parking_amount_cash + parking_amount_cheque'));
        })->sum();

        // New variable for storing parking data
        $parkingData = [
            'type' => 'Parking',
            'totalSqFt' => $totalParkingCount,
            'salesSqFt' => $soldParkingCount,
            'saleAmount' => $saleAmountParking,
            'balanceSqFt' => $availableParkingCount,
            'expectedBalanceAmount' => $expectedBalanceAmountParking,
        ];

        // Now, calculate room type data for 'Flats', 'Shops', 'Chairspaces', and 'Kiosk'
        $rooms = $building->rooms;
        $roomTypeData = [];

        // Calculate Flats data
        $flats = $rooms->where('room_type', 'Flats');

        $totalFlatSqFt = $flats->sum('flat_build_up_area');
        $soldFlatSqFt = $flats->where('status', 'sold')->sum('flat_build_up_area');
        $soldFlatAmount = $flats->where('status', 'sold')->sum('total_amount');
        $availableFlatSqFt = $flats->where('status', 'available')->sum('flat_build_up_area');
        $expectedBalanceAmountFlats = $flats->where('status', 'available')->sum('expected_amount');

        // Storing the data for Flats room type
        $roomTypeData[] = [
            'type' => 'Flats',
            'totalSqFt' => $totalFlatSqFt,
            'salesSqFt' => $soldFlatSqFt,
            'saleAmount' => $soldFlatAmount,
            'balanceSqFt' => $availableFlatSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountFlats,
        ];

        // Calculate Shops data
        $shops = $rooms->where('room_type', 'Shops');

        $totalShopSqFt = $shops->sum('build_up_area');
        $soldShopSqFt = $shops->where('status', 'sold')->sum('build_up_area');
        $soldShopAmount = $shops->where('status', 'sold')->sum('total_amount');
        $availableShopSqFt = $shops->where('status', 'available')->sum('build_up_area');
        $expectedBalanceAmountShops = $shops->where('status', 'available')->sum('expected_amount');

        // Storing the data for Shops room type
        $roomTypeData[] = [
            'type' => 'Shops',
            'totalSqFt' => $totalShopSqFt,
            'salesSqFt' => $soldShopSqFt,
            'saleAmount' => $soldShopAmount,
            'balanceSqFt' => $availableShopSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountShops,
        ];

        // Calculate Chairspaces data (use 'chair_space_in_sq' instead of 'space_area')
        $chairspaces = $rooms->where('room_type', 'Chairspaces');

        $totalChairspaceSqFt = $chairspaces->sum('chair_space_in_sq'); // Adjusted field name
        $soldChairspaceSqFt = $chairspaces->where('status', 'sold')->sum('chair_space_in_sq'); // Adjusted field name
        $soldChairspaceAmount = $chairspaces->where('status', 'sold')->sum('total_amount');
        $availableChairspaceSqFt = $chairspaces->where('status', 'available')->sum('chair_space_in_sq'); // Adjusted field name
        $expectedBalanceAmountChairspaces = $chairspaces->where('status', 'available')->sum('expected_amount');

        // Storing the data for Chairspaces room type
        $roomTypeData[] = [
            'type' => 'Chairspaces',
            'totalSqFt' => $totalChairspaceSqFt,
            'salesSqFt' => $soldChairspaceSqFt,
            'saleAmount' => $soldChairspaceAmount,
            'balanceSqFt' => $availableChairspaceSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountChairspaces,
        ];

        // Calculate Kiosk data
        $kiosks = $rooms->where('room_type', 'Kiosks');

        $totalKioskSqFt = $kiosks->sum('kiosk_area');  // Assuming 'kiosk_area' is the field for kiosks
        $soldKioskSqFt = $kiosks->where('status', 'sold')->sum('kiosk_area');
        $soldKioskAmount = $kiosks->where('status', 'sold')->sum('total_amount');
        $availableKioskSqFt = $kiosks->where('status', 'available')->sum('kiosk_area');
        $expectedBalanceAmountKiosks = $kiosks->where('status', 'available')->sum('expected_amount');

        // Storing the data for Kiosk room type
        $roomTypeData[] = [
            'type' => 'Kiosk',
            'totalSqFt' => $totalKioskSqFt,
            'salesSqFt' => $soldKioskSqFt,
            'saleAmount' => $soldKioskAmount,
            'balanceSqFt' => $availableKioskSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountKiosks,
        ];

        // Calculate TableSpace data
        $tablespaces = $rooms->where('room_type', 'Tablespaces');  // Assuming 'TableSpace' is the room type name

        $totalTableSpaceSqFt = $tablespaces->sum('space_area');  // Assuming 'space_area' is the field for tablespace
        $soldTableSpaceSqFt = $tablespaces->where('status', 'sold')->sum('space_area');
        $soldTableSpaceAmount = $tablespaces->where('status', 'sold')->sum('total_amount');
        $availableTableSpaceSqFt = $tablespaces->where('status', 'available')->sum('space_area');
        $expectedBalanceAmountTableSpaces = $tablespaces->where('status', 'available')->sum('expected_amount');


        // Storing the data for TableSpace room type
        $roomTypeData[] = [
            'type' => 'Tablespace',
            'totalSqFt' => $totalTableSpaceSqFt,
            'salesSqFt' => $soldTableSpaceSqFt,
            'saleAmount' => $soldTableSpaceAmount,
            'balanceSqFt' => $availableTableSpaceSqFt,
            'expectedBalanceAmount' => $expectedBalanceAmountTableSpaces,
        ];


        // Handle other room types that are not Flats, Shops, TableSpaces, ChairSpaces, or Kiosks
        $otherRooms = $rooms->whereNotIn('room_type', ['Flats', 'Shops', 'Tablespaces', 'Chairspaces', 'Kiosks']);

        foreach ($otherRooms as $room) {
            // Summing data for other room types (assuming 'space_area' is used for all other room types)
            $totalOtherRoomSqFt = $room->custom_area; // Adjust based on the column name used for area
            $soldOtherRoomSqFt = $room->status == 'sold' ? $room->custom_area : 0;
            $soldOtherRoomAmount = $room->status == 'sold' ? $room->total_amount : 0;
            $availableOtherRoomSqFt = $room->status == 'available' ? $room->custom_area : 0;
            $expectedBalanceAmountOtherRooms = $room->status == 'available' ? $room->expected_amount : 0;

            // Storing the data for other room types
            $roomTypeData[] = [
                'type' => $room->room_type,
                'totalSqFt' => $totalOtherRoomSqFt,
                'salesSqFt' => $soldOtherRoomSqFt,
                'saleAmount' => $soldOtherRoomAmount,
                'balanceSqFt' => $availableOtherRoomSqFt,
                'expectedBalanceAmount' => $expectedBalanceAmountOtherRooms,
            ];
        }

        $pdf = PDF::loadView('pdf.balance_summary_break_up_pdf',compact('building','parkingData', 'roomTypeData'));

        return $pdf->download('balance_summary_break_up.pdf');


    }
}