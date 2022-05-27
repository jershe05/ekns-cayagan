<?php
namespace App\Domains\Household\Http\Controllers;

use App\Domains\Household\Models\Household;

class HouseholdController
{
    public function delete(Household $household)
    {
        foreach($household->families as $family)
        {
            $family->delete();
        }

        $household->delete();

        return redirect()->back()->withFlashSuccess('Household Successfully deleted!');
    }

    public function index()
    {
        return view('backend.household.index');
    }
}
