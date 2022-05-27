<?php

namespace App\Domains\Analytics\Http\Controllers;

use App\Domains\Analytics\Http\Requests\StoreTotalVotersRequests;
use App\Domains\Analytics\Models\TotalVotersPerLocation;

class VotersAnalyticsController
{

    public function totalVoters()
    {
        return view('backend.analytics.index');
    }

    public function storeTotalVoters(StoreTotalVotersRequests $request)
    {
        $barangay = TotalVotersPerLocation::where('barangay_code', $request->get('address')['barangay_code'])->first();
        if($barangay)
        {
            $barangay->total_voters = $request->get('total_voters');
            $barangay->save();
            return redirect()->back()->withFlashSuccess('Number of Voters Updated.');
        }

        TotalVotersPerLocation::updateOrCreate([
            'total_voters' => $request->get('total_voters'),
            'barangay_code' => $request->get('address')['barangay_code'],
            'city_code' => $request->get('address')['city_code'],
            'province_code' => $request->get('address')['province_code'],
            'region_code' => $request->get('address')['region_code'],
        ]);

        return redirect()->back()->withFlashSuccess('Number of Voters Added.');
    }
}
