<?php

namespace App\Domains\Voter\Http\Controllers\Backend;

use App\Domains\Auth\Http\Controllers\Api\Traits\UserMisc;
use App\Domains\Auth\Models\User;
use App\Domains\Messages\Actions\ProcessSendOTP;
use App\Domains\Voter\Actions\StoreVoterAction;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\Actions\UpdateModelAddressAction;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Misc\Models\Precinct;
use App\Domains\Voter\Actions\UpdateVoterAction;
use App\Domains\Voter\Http\Requests\Backend\RegisterVoterRequest;
use App\Domains\Voter\Http\Resources\VoterResource;
use App\Domains\Voter\Models\ImportVoter;
use App\Domains\Voter\Models\ImportVotersPerSheet;
use Excel;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tzsk\Otp\Facades\Otp;

class VoterController
{
    use ApiResponseHelpers;
    use UserMisc;
    public function index()
    {
        return view('backend.Voter.index');
    }

    public function tagging()
    {
        return view('backend.Voter.tagging');
    }

    public function showVoterRegistrationForm()
    {
        abort_unless(config('boilerplate.access.user.registration'), 404);

        return view('frontend.voter.register')
            ->with('precincts', Precinct::all());;
    }

    public function store(RegisterVoterRequest $request)
    {
        $voter = (new StoreVoterAction)($request->data());
        $address = (new StoreModelAddressAction)($request->data()->address, $voter);
        $otp = Otp::digits(4)->expiry(10)->generate($request->data()->phone);
        $content = "OTP: $otp";
        // (new ProcessSendOTP)($content, $voter);
        return redirect()->route('frontend.voter.otp', $voter)->withFlashSuccess('Please provide the 4 digit OTP that was sent to '. $request->data()->phone . ' -  ' . $otp);
    }

    public function confirmOtp(Request $request, User $voter)
    {
        $digits = $request->get('digit-1') . $request->get('digit-2') . $request->get('digit-3') .$request->get('digit-4');
        $isMatched = Otp::digits(4)->expiry(10)->check($digits, $voter->phone);
        if($isMatched)
        {
            $voter->email_verified_at = Carbon::now()->toDateString();
            $voter->save();
            $voter->refresh();
            return redirect()->route('frontend.voter.registered', $voter)->withFlashSuccess("Successful Registration!");
        }

        return redirect()->back()->withErrors('Wrong OTP!');
    }

    public function voterRegistered(User $voter)
    {
        return view('frontend.voter.registered');
    }

    public function otp(User $voter)
    {
        return view('frontend.otp.index')
            ->with('voter', $voter);
    }

    public function update(RegisterVoterRequest $request, User $voter)
    {
        $voter = (new UpdateVoterAction)($request->data(), $voter);
        $address = (new UpdateModelAddressAction)($request->data()->address, $voter);

        return  $this->respondCreated([
            'voter' => new VoterResource($voter),
            'address' => new AddressResource($address)
        ]);
    }

    public function delete(User $voter)
    {
        $voter->delete();

        return  $this->respondCreated([
            'voter' => new VoterResource($voter),
            'address' => $this->address($voter)
        ]);
    }

    public function list(User $leader)
    {
        $voters = $leader->voters()->get();

        return $this->respondWithSuccess([
            'number_voters' => count($voters),
            'voters' => $voters
        ]);
    }

    public function search($referred_by, $keyword)
    {
        return $this->searchUser($keyword, $referred_by, 'voters');
    }

    public function uploadExcel(Request $request)
    {
        Storage::disk('s3')->delete('ekns/upload/voters_list.xlsx');
        $result = Storage::disk('s3')->copy($request->key, 'ekns/upload/voters_list.xlsx');

        return $this->respondWithSuccess([
            'result' => $result
        ]);

    }
    public function importVotersFromExcel(Request $request)
    {
        try {
            Excel::import(new ImportVotersPerSheet(), $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             return view('backend.Voter.import-voter')->with('failures', $failures);
        }
        return redirect()->back();
        // return view('backend.Voter.import-voter');
    }

}
