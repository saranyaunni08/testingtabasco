<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

use Barryvdh\DomPDF\Facade\Pdf;


class AccountsPayableController extends Controller
{
    public function statementall($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Statement All';
        $page = 'statement all';
    
        // Check if the user requested a download
        if (request()->has('download')) {
            // Prepare the data for the PDF
            $data = compact('building', 'title', 'page');
    
            // Generate the PDF
            $pdf = Pdf::loadView('Accountspayable.statementall', $data);
    
            // Download the PDF
            return $pdf->download('statement_all.pdf');
        }
    
        // Render the normal view
        return view('Accountspayable.statementall', compact('building', 'title', 'page'));
    }

    public function statementcash($buildingId){
        $building = Building::findOrFail($buildingId);
        $title = 'Statement Cash';
        $page = 'statement cash';

        return view('Accountspayable.statementcash',compact(
            'building',
            'title',
            'page'
        ));

    }

    public function statementcheque($buildingId){
        $building = Building::findOrFail($buildingId);
        $title = 'Statement Cheque';
        $page = 'statement cheque';

        return view('Accountspayable.statementcheque',compact(
            'building',
            'title',
            'page'
        ));
    }
   


    

}
