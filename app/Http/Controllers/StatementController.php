<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class StatementController extends Controller
{
    public function cash(Sale $sale)
    {
        $cashInstallments = $sale->cash_installments;
        $firstInstallmentDate = $cashInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $cashInstallments->last()->installment_date ?? now();
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount'); // Calculate total credit amount
        $totalDebit = $cashInstallments->sum('debit');
        $balance = $totalDebit - $totalCredit;

        return view('statements.cash', [
            'sale' => $sale,
            'cashInstallments' => $cashInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalCredit' => $totalCredit, // Pass total credit to the view
            'page' => 'cash',
            'title' => 'Cash Statement',
            'balance'=>$balance,
        ]);
    }

        
    public function downloadCashStatement(Sale $sale)
    {
        $cashInstallments = $sale->cash_installments;
        
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount');
        $totalDebit = $cashInstallments->sum('debit');
        $balance = $totalDebit - $totalCredit;

        $firstInstallmentDate = $cashInstallments->first()->installment_date;
        $lastInstallmentDate = $cashInstallments->last()->installment_date;

        $pdf = Pdf::loadView('pdf.cash_pdf', compact(
            'sale', 'cashInstallments', 'totalDebit', 'totalCredit', 'balance', 'firstInstallmentDate', 'lastInstallmentDate'
        ));
        

        return $pdf->download('cash_statement.pdf');
    }

    

    public function cheque(Sale $sale)
    {
        $chequeInstallments = $sale->installments;
        $firstInstallmentDate = $chequeInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $chequeInstallments->last()->installment_date ?? now();
        
        // Calculate the total debit and credit
        $totalDebit = $chequeInstallments->sum('debit');
        $totalCredit = $chequeInstallments->where('status', 'paid')->sum('installment_amount');
    
        return view('statements.cheque', [
            'sale' => $sale,
            'chequeInstallments' => $chequeInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'page' => 'cheque',
            'title' => 'Cheque Statement',
        ]);
    }
    

    public function all(Sale $sale)
    {
        return view('statements.all', [
            'sale' => $sale,
            'page' => 'all', // Page identifier
        ]);
    }

    public function summary(Sale $sale)
    {
        return view('statements.summary', [
            'sale' => $sale,
            'page' => 'summary', // Page identifier
        ]);
    }
}
