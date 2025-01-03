<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Sale;
use App\Models\SaleReturn;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class ExchangeReportController extends Controller
{
    public function exchangereturnreport($buildingId)
    {
        // Fetch the building by ID
        $building = Building::findOrFail($buildingId);
    
        // Fetch sales where exchange_status = 'EX'
        $sales = Sale::where('exchangestatus', 'EX')
            ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
            ->select(
                'sales.customer_name', 
                'sales.sale_amount', 
                'sales.exchange_sale_id', 
                'rooms.room_floor', 
                'rooms.room_number', 
                'rooms.room_type', 
                'rooms.flat_build_up_area',
                'rooms.build_up_area',
                'rooms.space_area',
                'rooms.kiosk_area',
                'rooms.chair_space_in_sq',
                'rooms.custom_area'
            )
            ->get()
            ->map(function ($sale) {
                // Determine the appropriate build-up area based on room type
                switch ($sale->room_type) {
                    case 'Flats':
                        $sale->build_up_area = $sale->flat_build_up_area ?? 0;
                        break;
                    case 'Shops':
                        $sale->build_up_area = $sale->build_up_area ?? 0;
                        break;
                    case 'Tablespaces':
                        $sale->build_up_area = $sale->space_area ?? 0;
                        break;
                    case 'Kiosks':
                        $sale->build_up_area = $sale->kiosk_area ?? 0;
                        break;
                    case 'Chairspaces':
                        $sale->build_up_area = $sale->chair_space_in_sq ?? 0;
                        break;
                    default:
                        $sale->build_up_area = $sale->custom_area ?? 0;
                        break;
                }
    
                // Fetch the exchanged sale details
                $exchangedSale = Sale::where('sales.id', $sale->exchange_sale_id)
                    ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
                    ->select(
                        'sales.customer_name as exchange_customer_name',
                        'sales.sale_amount as exchange_sale_amount',
                        'rooms.room_floor as exchange_room_floor',
                        'rooms.room_number as exchange_room_number',
                        'rooms.room_type as exchange_room_type',
                        'rooms.flat_build_up_area as exchange_flat_build_up_area',
                        'rooms.build_up_area as exchange_build_up_area',
                        'rooms.space_area as exchange_space_area',
                        'rooms.kiosk_area as exchange_kiosk_area',
                        'rooms.chair_space_in_sq as exchange_chair_space_in_sq',
                        'rooms.custom_area as exchange_custom_area'
                    )
                    ->first();
    
                if ($exchangedSale) {
                    // Determine the appropriate build-up area for the exchanged sale
                    switch ($exchangedSale->exchange_room_type) {
                        case 'Flats':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_flat_build_up_area ?? 0;
                            break;
                        case 'Shops':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_build_up_area ?? 0;
                            break;
                        case 'Tablespaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_space_area ?? 0;
                            break;
                        case 'Kiosks':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_kiosk_area ?? 0;
                            break;
                        case 'Chairspaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_chair_space_in_sq ?? 0;
                            break;
                        default:
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_custom_area ?? 0;
                            break;
                    }
                }
    
                // Attach exchanged sale details to the current sale
                $sale->exchangedSale = $exchangedSale;
    
                return $sale;
            });
    
        // Sort the sales by room_floor in ascending order
        $sales = $sales->sortBy('room_floor');
    
        // Also sort the exchanged room_floor in ascending order, if available
        $sales = $sales->map(function ($sale) {
            if ($sale->exchangedSale) {
                $sale->exchangedSale = collect([$sale->exchangedSale])->sortBy('exchange_room_floor')->first();
            }
            return $sale;
        });
    
        // Title and page data
        $title = 'Exchange-Report';
        $page = 'exchange report';
    
        // Return the view with the data
        return view('exchangereport.exchange_report', compact(
            'building',
            'sales',  // Pass sales data to the view
            'title',
            'page'
        ));
    }
    


    public function exchangeandreturnsummary($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Exchange And Return Summary';
        $page = 'exchange and return summary';
    
        // Fetch SaleReturn data with associated Sale and Room data
        $saleReturns = SaleReturn::with(['sale.room'])
            ->get()
            ->map(function ($saleReturn) {
                $sale = $saleReturn->sale;
                $room = $sale ? $sale->room : null;
    
                // Determine the build-up area based on room type
                $buildUpArea = 1; // Default to 1 for multiplication if no specific area is found
                if ($room) {
                    switch ($room->room_type) {
                        case 'flat':
                            $buildUpArea = $room->flat_build_up_area ?? 1;
                            break;
                        case 'shops':
                            $buildUpArea = $room->build_up_area ?? 1;
                            break;
                        case 'table space':
                            $buildUpArea = $room->space_area ?? 1;
                            break;
                        case 'kiosk':
                            $buildUpArea = $room->kiosk_area ?? 1;
                            break;
                        case 'chair space':
                            $buildUpArea = $room->chair_space_in_sq ?? 1;
                            break;
                        default:
                            $buildUpArea = $room->custom_area ?? 1;
                            break;
                    }
                }
    
                // Calculate total sale amount
                $saleReturn->total_sale_amount = ($sale->sale_amount ?? 0) * $buildUpArea;
    
                return $saleReturn;
            });
    
        // Fetch Exchange summary data
        $exchangeSales = Sale::where('exchangestatus', 'EX')
            ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
            ->select(
                'sales.customer_name', 
                'sales.sale_amount', 
                'sales.exchange_sale_id', 
                'rooms.room_floor', 
                'rooms.room_number', 
                'rooms.room_type', 
                'rooms.flat_build_up_area',
                'rooms.build_up_area',
                'rooms.space_area',
                'rooms.kiosk_area',
                'rooms.chair_space_in_sq',
                'rooms.custom_area'
            )
            ->get()
            ->map(function ($sale) {
                // Determine the appropriate build-up area based on room type
                switch ($sale->room_type) {
                    case 'Flats':
                        $sale->build_up_area = $sale->flat_build_up_area ?? 0;
                        break;
                    case 'Shops':
                        $sale->build_up_area = $sale->build_up_area ?? 0;
                        break;
                    case 'Tablespaces':
                        $sale->build_up_area = $sale->space_area ?? 0;
                        break;
                    case 'Kiosks':
                        $sale->build_up_area = $sale->kiosk_area ?? 0;
                        break;
                    case 'Chairspaces':
                        $sale->build_up_area = $sale->chair_space_in_sq ?? 0;
                        break;
                    default:
                        $sale->build_up_area = $sale->custom_area ?? 0;
                        break;
                }
    
                // Fetch the exchanged sale details
                $exchangedSale = Sale::where('sales.id', $sale->exchange_sale_id)
                    ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
                    ->select(
                        'sales.customer_name as exchange_customer_name',
                        'sales.sale_amount as exchange_sale_amount',
                        'rooms.room_floor as exchange_room_floor',
                        'rooms.room_number as exchange_room_number',
                        'rooms.room_type as exchange_room_type',
                        'rooms.flat_build_up_area as exchange_flat_build_up_area',
                        'rooms.build_up_area as exchange_build_up_area',
                        'rooms.space_area as exchange_space_area',
                        'rooms.kiosk_area as exchange_kiosk_area',
                        'rooms.chair_space_in_sq as exchange_chair_space_in_sq',
                        'rooms.custom_area as exchange_custom_area'
                    )
                    ->first();
    
                if ($exchangedSale) {
                    // Determine the appropriate build-up area for the exchanged sale
                    switch ($exchangedSale->exchange_room_type) {
                        case 'Flats':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_flat_build_up_area ?? 0;
                            break;
                        case 'Shops':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_build_up_area ?? 0;
                            break;
                        case 'Tablespaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_space_area ?? 0;
                            break;
                        case 'Kiosks':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_kiosk_area ?? 0;
                            break;
                        case 'Chairspaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_chair_space_in_sq ?? 0;
                            break;
                        default:
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_custom_area ?? 0;
                            break;
                    }
                }
    
                // Attach exchanged sale details to the current sale
                $sale->exchangedSale = $exchangedSale;
    
                return $sale;
            });
    
        // Return view with the data
        return view('exchangereport.exchangeandreturnsummary', compact(
            'title',
            'page',
            'saleReturns',
            'exchangeSales',
            'building'
        ));
    }

       public function generatePDF($buildingId)
    {
        // Fetch the building by ID
        $building = Building::findOrFail($buildingId);
    
        // Fetch sales where exchange_status = 'EX'
        $sales = Sale::where('exchangestatus', 'EX')
            ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
            ->select(
                'sales.customer_name', 
                'sales.sale_amount', 
                'sales.exchange_sale_id', 
                'rooms.room_floor', 
                'rooms.room_number', 
                'rooms.room_type', 
                'rooms.flat_build_up_area',
                'rooms.build_up_area',
                'rooms.space_area',
                'rooms.kiosk_area',
                'rooms.chair_space_in_sq',
                'rooms.custom_area'
            )
            ->get()
            ->map(function ($sale) {
                // Determine the appropriate build-up area based on room type
                switch ($sale->room_type) {
                    case 'Flats':
                        $sale->build_up_area = $sale->flat_build_up_area ?? 0;
                        break;
                    case 'Shops':
                        $sale->build_up_area = $sale->build_up_area ?? 0;
                        break;
                    case 'Tablespaces':
                        $sale->build_up_area = $sale->space_area ?? 0;
                        break;
                    case 'Kiosks':
                        $sale->build_up_area = $sale->kiosk_area ?? 0;
                        break;
                    case 'Chairspaces':
                        $sale->build_up_area = $sale->chair_space_in_sq ?? 0;
                        break;
                    default:
                        $sale->build_up_area = $sale->custom_area ?? 0;
                        break;
                }
    
                // Fetch the exchanged sale details
                $exchangedSale = Sale::where('sales.id', $sale->exchange_sale_id)
                    ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
                    ->select(
                        'sales.customer_name as exchange_customer_name',
                        'sales.sale_amount as exchange_sale_amount',
                        'rooms.room_floor as exchange_room_floor',
                        'rooms.room_number as exchange_room_number',
                        'rooms.room_type as exchange_room_type',
                        'rooms.flat_build_up_area as exchange_flat_build_up_area',
                        'rooms.build_up_area as exchange_build_up_area',
                        'rooms.space_area as exchange_space_area',
                        'rooms.kiosk_area as exchange_kiosk_area',
                        'rooms.chair_space_in_sq as exchange_chair_space_in_sq',
                        'rooms.custom_area as exchange_custom_area'
                    )
                    ->first();
    
                if ($exchangedSale) {
                    // Determine the appropriate build-up area for the exchanged sale
                    switch ($exchangedSale->exchange_room_type) {
                        case 'Flats':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_flat_build_up_area ?? 0;
                            break;
                        case 'Shops':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_build_up_area ?? 0;
                            break;
                        case 'Tablespaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_space_area ?? 0;
                            break;
                        case 'Kiosks':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_kiosk_area ?? 0;
                            break;
                        case 'Chairspaces':
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_chair_space_in_sq ?? 0;
                            break;
                        default:
                            $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_custom_area ?? 0;
                            break;
                    }
                }
    
                // Attach exchanged sale details to the current sale
                $sale->exchangedSale = $exchangedSale;
    
                return $sale;
            });
    
        // Sort the sales by room_floor in ascending order
        $sales = $sales->sortBy('room_floor');
    
        // Also sort the exchanged room_floor in ascending order, if available
        $sales = $sales->map(function ($sale) {
            if ($sale->exchangedSale) {
                $sale->exchangedSale = collect([$sale->exchangedSale])->sortBy('exchange_room_floor')->first();
            }
            return $sale;
        });
    
    
        // Load the view with data
        $pdf = PDF::loadView('pdf.exchange_report_pdf', compact('building', 'sales'));
    
        // Return the generated PDF
        return $pdf->download('exchange_report.pdf');
    }
    

   public function exchangeandreturnsummaryPdf($buildingId){
           
        
         $building = Building::findOrFail($buildingId);
          // Fetch SaleReturn data with associated Sale and Room data
          $saleReturns = SaleReturn::with(['sale.room'])
          ->get()
          ->map(function ($saleReturn) {
              $sale = $saleReturn->sale;
              $room = $sale ? $sale->room : null;
  
              // Determine the build-up area based on room type
              $buildUpArea = 1; // Default to 1 for multiplication if no specific area is found
              if ($room) {
                  switch ($room->room_type) {
                      case 'flat':
                          $buildUpArea = $room->flat_build_up_area ?? 1;
                          break;
                      case 'shops':
                          $buildUpArea = $room->build_up_area ?? 1;
                          break;
                      case 'table space':
                          $buildUpArea = $room->space_area ?? 1;
                          break;
                      case 'kiosk':
                          $buildUpArea = $room->kiosk_area ?? 1;
                          break;
                      case 'chair space':
                          $buildUpArea = $room->chair_space_in_sq ?? 1;
                          break;
                      default:
                          $buildUpArea = $room->custom_area ?? 1;
                          break;
                  }
              }
  
              // Calculate total sale amount
              $saleReturn->total_sale_amount = ($sale->sale_amount ?? 0) * $buildUpArea;
  
              return $saleReturn;
          });
  
      // Fetch Exchange summary data
      $exchangeSales = Sale::where('exchangestatus', 'EX')
          ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
          ->select(
              'sales.customer_name', 
              'sales.sale_amount', 
              'sales.exchange_sale_id', 
              'rooms.room_floor', 
              'rooms.room_number', 
              'rooms.room_type', 
              'rooms.flat_build_up_area',
              'rooms.build_up_area',
              'rooms.space_area',
              'rooms.kiosk_area',
              'rooms.chair_space_in_sq',
              'rooms.custom_area'
          )
          ->get()
          ->map(function ($sale) {
              // Determine the appropriate build-up area based on room type
              switch ($sale->room_type) {
                  case 'Flats':
                      $sale->build_up_area = $sale->flat_build_up_area ?? 0;
                      break;
                  case 'Shops':
                      $sale->build_up_area = $sale->build_up_area ?? 0;
                      break;
                  case 'Tablespaces':
                      $sale->build_up_area = $sale->space_area ?? 0;
                      break;
                  case 'Kiosks':
                      $sale->build_up_area = $sale->kiosk_area ?? 0;
                      break;
                  case 'Chairspaces':
                      $sale->build_up_area = $sale->chair_space_in_sq ?? 0;
                      break;
                  default:
                      $sale->build_up_area = $sale->custom_area ?? 0;
                      break;
              }
  
              // Fetch the exchanged sale details
              $exchangedSale = Sale::where('sales.id', $sale->exchange_sale_id)
                  ->join('rooms', 'sales.room_id', '=', 'rooms.id') // Join with rooms table
                  ->select(
                      'sales.customer_name as exchange_customer_name',
                      'sales.sale_amount as exchange_sale_amount',
                      'rooms.room_floor as exchange_room_floor',
                      'rooms.room_number as exchange_room_number',
                      'rooms.room_type as exchange_room_type',
                      'rooms.flat_build_up_area as exchange_flat_build_up_area',
                      'rooms.build_up_area as exchange_build_up_area',
                      'rooms.space_area as exchange_space_area',
                      'rooms.kiosk_area as exchange_kiosk_area',
                      'rooms.chair_space_in_sq as exchange_chair_space_in_sq',
                      'rooms.custom_area as exchange_custom_area'
                  )
                  ->first();
  
              if ($exchangedSale) {
                  // Determine the appropriate build-up area for the exchanged sale
                  switch ($exchangedSale->exchange_room_type) {
                      case 'Flats':
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_flat_build_up_area ?? 0;
                          break;
                      case 'Shops':
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_build_up_area ?? 0;
                          break;
                      case 'Tablespaces':
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_space_area ?? 0;
                          break;
                      case 'Kiosks':
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_kiosk_area ?? 0;
                          break;
                      case 'Chairspaces':
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_chair_space_in_sq ?? 0;
                          break;
                      default:
                          $exchangedSale->exchange_build_up_area = $exchangedSale->exchange_custom_area ?? 0;
                          break;
                  }
              }
  
              // Attach exchanged sale details to the current sale
              $sale->exchangedSale = $exchangedSale;
  
              return $sale;
          });
           // Load the view with data
        $pdf = PDF::loadView('pdf.exchange_return_summary_pdf', compact('building',   'saleReturns',
        'exchangeSales',));
    
        // Return the generated PDF
        return $pdf->download('exchangeandreturnsummary.pdf');
   }
}
