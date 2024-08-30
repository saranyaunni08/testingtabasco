<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\Installment;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_contact' => 'required|string|max:20',
            'area_calculation_type' => 'required|string',
            'sale_amount' => 'required|numeric',
            'calculation_type' => 'nullable|string',
            'parking_rate_per_sq_ft' => 'nullable|numeric',
            'total_sq_ft_for_parking' => 'nullable|numeric',
            'gst_percent' => 'required|numeric',
            'advance_payment' => 'required|string',
            'advance_amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'transfer_id' => 'nullable|string',
            'cheque_id' => 'nullable|string',
            'last_date' => 'nullable|date',
            'discount_percent' => 'nullable|numeric',
            'installments' => 'required|numeric',
            'installment_date' => 'nullable|date',
            'cash_in_hand_percent' => 'nullable|numeric',
            'in_hand_amount' => 'nullable|numeric',
            'cash_in_hand_paid_amount' => 'nullable|numeric',
            'cash_in_hand_status' => 'nullable|string',
            'cash_in_hand_partner_name' => 'nullable|string',
        ]);

        $room = Room::find($validatedData['room_id']);

        $roomRate = $this->calculateRoomRate($validatedData, $room);
        $parkingAmount = $this->calculateParkingAmount($validatedData);
        $totalAmount = $roomRate + $parkingAmount;
        $amountForGst = $totalAmount - ($validatedData['in_hand_amount'] ?? 0);
        $gstAmount = $amountForGst * ($validatedData['gst_percent'] / 100);
        $totalWithGst = $totalAmount + $gstAmount;
        $totalWithDiscount = isset($validatedData['discount_percent']) ? $totalWithGst - ($totalWithGst * ($validatedData['discount_percent'] / 100)) : $totalWithGst;
        $remainingBalance = $totalWithDiscount - ($validatedData['advance_amount'] ?? 0);

        $sale = new Sale();
        $sale->fill($validatedData);
        $sale->room_rate = $roomRate;
        $sale->total_amount = $totalAmount;
        $sale->parking_amount = $parkingAmount;
        $sale->gst_amount = $gstAmount;
        $sale->total_with_gst = $totalWithGst;
        $sale->total_with_discount = $totalWithDiscount;
        $sale->remaining_balance = $remainingBalance;
        $sale->status = 'cancel';
        $sale->save();

        $room->status = 'sold';
        $room->save();

        $installmentAmount = $remainingBalance / $validatedData['installments'];
        $installmentDate = Carbon::parse($validatedData['installment_date']);

        for ($i = 0; $i < $validatedData['installments']; $i++) {
            Installment::create([
                'sale_id' => $sale->id,
                'installment_date' => $installmentDate->copy()->addMonths($i),
                'installment_amount' => $installmentAmount,
                'transaction_details' => '',
                'bank_details' => '',
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Room sold successfully!');
    }

    protected function getAreaProperty($room, $areaCalculationType)
    {
        $areaProperties = [
            'Shops' => ['carpet_area', 'build_up_area'],
            'Flat' => ['flat_carpet_area', 'flat_build_up_area'],
            'Table space' => ['space_area', 'space_area'],
            'Chair space' => ['chair_space_in_sq', 'chair_space_in_sq'],
            'Kiosk' => ['kiosk_area', 'kiosk_area'],
        ];

        if (array_key_exists($room->room_type, $areaProperties)) {
            $propertyIndex = ($areaCalculationType == 'carpet_area_rate') ? 0 : 1;
            return $areaProperties[$room->room_type][$propertyIndex];
        }

        return null;
    }

    protected function calculateRoomRate($validatedData, $room)
    {
        $areaProperty = $this->getAreaProperty($room, $validatedData['area_calculation_type']);
        return isset($room->$areaProperty) ? $validatedData['sale_amount'] * $room->$areaProperty : 0;
    }

    protected function calculateParkingAmount($validatedData)
    {
        if ($validatedData['calculation_type'] == 'fixed_amount') {
            return 0;
        } elseif ($validatedData['calculation_type'] == 'rate_per_sq_ft' && 
                  !is_null($validatedData['total_sq_ft_for_parking']) && 
                  !is_null($validatedData['parking_rate_per_sq_ft'])) {
            return $validatedData['total_sq_ft_for_parking'] * $validatedData['parking_rate_per_sq_ft'];
        }
        return 0;
    }

    public function create()
    {
        $rooms = Room::all();
        $page = 'create';
        return view('sales.create', compact('rooms', 'page'));
    }

    public function showSales()
    {
        $sales = Sale::all();
        $page = 'sales';
        return view('sales.sales', compact('sales', 'page'));
    }

    public function softDelete($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        $room = Room::findOrFail($sale->room_id);
        $room->status = 'available';
        $room->save();

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $customerNames = Sale::pluck('customer_name')->unique();
        $salesQuery = Sale::query();

        if ($search) {
            $salesQuery->where('customer_name', 'like', '%' . $search . '%');
        }

        $sales = $salesQuery->paginate(10);
        return view('customers.index', compact('customerNames', 'sales', 'search'));
    }

    public function showCustomer($saleId)
    {
        $sale = Sale::with('room', 'installments')->findOrFail($saleId);
        $installments = Installment::where('sale_id', $saleId)->get(); 
        $room = Room::find($sale->room_id);

        $totalPaidInstallments = $installments->where('status', 'paid')->sum('installment_amount');
        $emi_amount = $installments->sum('installment_amount');
        $tenure_months = $installments->count();
        $emi_start_date = $installments->first()->installment_date ?? null;
        $emi_end_date = $installments->last()->installment_date ?? null;

        $remainingBalanceAfterInstallments = $sale->remaining_balance - $totalPaidInstallments;
        $page = 'customer';

        // If the view expects multiple sales, wrap the single sale in a collection
        $sales = collect([$sale]);

        return view('customers.show', compact(
            'sales', 'installments', 'page',
            'remainingBalanceAfterInstallments', 'emi_amount', 'tenure_months',
            'emi_start_date', 'emi_end_date',
            'room',
        ));
    }

    public function getCalculationType(Request $request)
    {
        $roomType = $request->input('room_type');
        $calculationType = $request->input('calculation_type');

        if ($roomType == 'Shops' || $roomType == 'Flat') {
            $type = $calculationType == 'carpet_area_rate' ? 'carpet_area' : 'build_up_area';
        } elseif ($roomType == 'Table space') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'space_area' : 'space_area';
        } elseif ($roomType == 'Chair space') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'chair_space_in_sq' : 'chair_space_in_sq';
        } elseif ($roomType == 'Kiosk') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'kiosk_area' : 'kiosk_area';
        } else {
            $type = null;
        }

        return response()->json(['type' => $type]);
    }

    public function downloadCsv()
    {
        $sales = Sale::all();
        $filename = 'sales_data_' . now()->format('Ymd_His') . '.csv';
        $csvWriter = Writer::createFromFileObject(new \SplTempFileObject());

        $csvWriter->insertOne([
            'ID', 'Customer Name', 'Room ID', 'Sale Amount', 'GST Amount', 'Total Amount', 'Remaining Balance', 'Status'
        ]);

        foreach ($sales as $sale) {
            $csvWriter->insertOne([
                $sale->id, $sale->customer_name, $sale->room_id, $sale->sale_amount, $sale->gst_amount, $sale->total_amount, $sale->remaining_balance, $sale->status
            ]);
        }

        $csvContent = $csvWriter->toString();
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadPdf($id)
    {
        $sale = Sale::findOrFail($id);
        $pdf = PDF::loadView('pdf.sale', ['sale' => $sale]);

        return $pdf->download('sale_' . $sale->id . '.pdf');
    }
}
