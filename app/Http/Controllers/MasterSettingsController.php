<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSetting;

class MasterSettingsController extends Controller
{
    public function index()
    {
        $settings = MasterSetting::first();
        $page = 'masters';
        $title = 'Master Settings';
        return view('masters.index', compact('settings', 'page', 'title'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'gst_flat' => 'nullable|numeric',
            'gst_shop' => 'nullable|numeric',
            'advance_payment_days' => 'required|numeric',
            'parking_fixed_rate' => 'nullable|numeric',
            'parking_rate_per_sq_ft' => 'nullable|numeric',
        ]);

        $settings = MasterSetting::firstOrCreate([]);

        $settings->gst_flat = $validatedData['gst_flat'];
        $settings->gst_shop = $validatedData['gst_shop'];
        $settings->advance_payment_days = $validatedData['advance_payment_days'];
        $settings->parking_fixed_rate = $validatedData['parking_fixed_rate'];
        $settings->parking_rate_per_sq_ft = $validatedData['parking_rate_per_sq_ft'];

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');

    }

    public function getGstPercentages()
    {
        $flatGst = MasterSetting::where('key', 'gst_flat')->value('value');
        $shopGst = MasterSetting::where('key', 'gst_shop')->value('value');

        return response()->json([
            'flat_gst' => $flatGst,
            'shop_gst' => $shopGst,
        ]);
    }
}
