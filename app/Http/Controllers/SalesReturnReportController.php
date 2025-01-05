<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\SaleReturn;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class SalesReturnReportController extends Controller
{
    public function returnreportall($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        $salesReturns = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'shops') // Filter for room_type = 'shops'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Shops'); // Ensure only rooms of type 'shops' are included
        })->get();

        $salesFlats = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Flats') // Filter for room_type = 'flats'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'flat_build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Flats');
        })->get();

        $salesKiosks = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Kiosks') // Filter for room_type = 'Kiosk'
                      ->select('id', 'room_type', 'room_number', 'room_floor', 'kiosk_area'); // Select kiosk_area instead of flat_build_up_area
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Kiosks');
        })->get();

        $salesTablespaces = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Tablespaces') // Filter for room_type = 'Tablespace'
                      ->select('id', 'room_type', 'room_number', 'room_floor', 'space_area'); // Select space_area instead of flat_build_up_area
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Tablespaces');
        })->get();

        $salesChairspaces = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Chairspace') // Filter for room_type = 'Chairspace'
                      ->select('id', 'room_type', 'room_number', 'room_floor', 'chair_space_in_sq'); // Select space_area instead of chair_space_in_sq
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Chairspace');
        })->get();

        $salesCustomspaces = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
            },
            'sale.room' => function ($query) {
                // Filter for rooms that are NOT flats, shops, chairspaces, tablesspaces, or kiosks
                $query->whereNotIn('room_type', ['Flats', 'Shops', 'Chairspaces', 'Tablesspaces', 'Kiosks'])
                      ->select('id', 'room_type', 'room_number', 'room_floor', 'custom_area'); // Select custom_area instead of space_area
            }
        ])->whereHas('sale.room', function ($query) {
            // Ensure rooms have room_type other than flats, shops, chairspaces, tablesspaces, and kiosks
            $query->whereNotIn('room_type', ['Flats', 'Shops', 'Chairspaces', 'Tablesspaces', 'Kiosks']);
        })->get();
        
        
        
        

        
        // Fetch parking details
        $parkingDetails = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'parking_id', 'customer_name', 'status','parking_amount_cash','parking_amount_cheque')
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
            'salesKiosks',
            'salesTablespaces',
            'salesChairspaces',
            'salesCustomspaces'
            
        ));
    }


    public function commercialreturn($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Commercial Sales Return Report';
        $page = 'commercial-sales-return-report';

        $salesReturns = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
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
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
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
                $query->select('id', 'parking_id', 'customer_name', 'status', 'parking_amount_cash','parking_amount_cheque')
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

    public function salesreturnallPDF($buildingId){


         // Fetch the building details
         $building = Building::findOrFail($buildingId);

         $salesReturns = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
             },
             'sale.room' => function ($query) {
                 $query->where('room_type', 'shops') // Filter for room_type = 'shops'
                     ->select('id', 'room_type', 'room_number', 'room_floor', 'build_up_area');
             }
         ])->whereHas('sale.room', function ($query) {
             $query->where('room_type', 'Shops'); // Ensure only rooms of type 'shops' are included
         })->get();
 
         $salesFlats = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
             },
             'sale.room' => function ($query) {
                 $query->where('room_type', 'Flats') // Filter for room_type = 'flats'
                     ->select('id', 'room_type', 'room_number', 'room_floor', 'flat_build_up_area');
             }
         ])->whereHas('sale.room', function ($query) {
             $query->where('room_type', 'Flats');
         })->get();
 
         $salesKiosks = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
             },
             'sale.room' => function ($query) {
                 $query->where('room_type', 'Kiosks') // Filter for room_type = 'Kiosk'
                       ->select('id', 'room_type', 'room_number', 'room_floor', 'kiosk_area'); // Select kiosk_area instead of flat_build_up_area
             }
         ])->whereHas('sale.room', function ($query) {
             $query->where('room_type', 'Kiosks');
         })->get();
 
         $salesTablespaces = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
             },
             'sale.room' => function ($query) {
                 $query->where('room_type', 'Tablespaces') // Filter for room_type = 'Tablespace'
                       ->select('id', 'room_type', 'room_number', 'room_floor', 'space_area'); // Select space_area instead of flat_build_up_area
             }
         ])->whereHas('sale.room', function ($query) {
             $query->where('room_type', 'Tablespaces');
         })->get();
 
         $salesChairspaces = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
             },
             'sale.room' => function ($query) {
                 $query->where('room_type', 'Chairspace') // Filter for room_type = 'Chairspace'
                       ->select('id', 'room_type', 'room_number', 'room_floor', 'chair_space_in_sq'); // Select space_area instead of chair_space_in_sq
             }
         ])->whereHas('sale.room', function ($query) {
             $query->where('room_type', 'Chairspace');
         })->get();
 
         $salesCustomspaces = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id', 'total_amount');
             },
             'sale.room' => function ($query) {
                 // Filter for rooms that are NOT flats, shops, chairspaces, tablesspaces, or kiosks
                 $query->whereNotIn('room_type', ['Flats', 'Shops', 'Chairspaces', 'Tablesspaces', 'Kiosks'])
                       ->select('id', 'room_type', 'room_number', 'room_floor', 'custom_area'); // Select custom_area instead of space_area
             }
         ])->whereHas('sale.room', function ($query) {
             // Ensure rooms have room_type other than flats, shops, chairspaces, tablesspaces, and kiosks
             $query->whereNotIn('room_type', ['Flats', 'Shops', 'Chairspaces', 'Tablesspaces', 'Kiosks']);
         })->get();
         
         
         
         // Fetch parking details
         $parkingDetails = SaleReturn::with([
             'sale' => function ($query) {
                 $query->select('id', 'parking_id', 'customer_name', 'status','parking_amount_cash','parking_amount_cheque')
                     ->whereNotNull('parking_id'); // Ensure parking_id is not null
             },
             'sale.parking' => function ($query) {
                 $query->select('id', 'slot_number', 'parking_floor'); // Fetch slot_number and parking_floor
             }
         ])->whereHas('sale', function ($query) {
             $query->whereNotNull('parking_id'); // Ensure parking_id is not null
         })->get();

         $pdf = PDF::loadView('pdf.sales_return_report_all_pdf', compact(
            'building',
            'salesReturns',
            'salesFlats',
            'parkingDetails',
            'salesKiosks',
            'salesTablespaces',
            'salesChairspaces',
            'salesCustomspaces'
        ));

        return $pdf->download('sales_return_report_all.pdf');
 
    }
    
    public function salesreturnparkingPDF($buildingId){

        $building = Building::findOrFail($buildingId);

          // Fetch parking details
          $parkingDetails = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'parking_id', 'customer_name', 'status', 'parking_amount_cash','parking_amount_cheque')
                    ->whereNotNull('parking_id'); // Ensure parking_id is not null
            },
            'sale.parking' => function ($query) {
                $query->select('id', 'slot_number', 'floor_number'); // Fetch slot_number and parking_floor
            }
        ])->whereHas('sale', function ($query) {
            $query->whereNotNull('parking_id'); // Ensure parking_id is not null
        })->get();

        $pdf = PDF::loadView('pdf.sales_return_parking_report_pdf', compact(
            'building',
            'parkingDetails'
        ));

        return $pdf->download('sales_return_parking_report.pdf');


    }

    public function salesreturncommercialPDF($buildingId){

        $building = Building::findOrFail($buildingId);

        $salesReturns = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'shops') // Filter for room_type = 'shops'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'shops'); // Ensure only rooms of type 'shops' are included
        })->get();


        $pdf = PDF::loadView('pdf.sales_return_commercial_report_pdf', compact(
            'building',
            'salesReturns'
        ));

        return $pdf->download('sales_return_commercial_report.pdf');
    }

    public function salesreturnapartmentPDF($buildingId){

        $building = Building::findOrFail($buildingId);     
        
        $salesFlats = SaleReturn::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_amount', 'customer_name', 'status', 'room_id','total_amount');
            },
            'sale.room' => function ($query) {
                $query->where('room_type', 'Flats') // Filter for room_type = 'flats'
                    ->select('id', 'room_type', 'room_number', 'room_floor', 'flat_build_up_area');
            }
        ])->whereHas('sale.room', function ($query) {
            $query->where('room_type', 'Flats');
        })->get();


        $pdf = PDF::loadView('pdf.sales_return_apartment_report_pdf', compact(
            'building',
            'salesFlats'
        ));

        return $pdf->download('sales_return_apartment_report.pdf');



    }
}
