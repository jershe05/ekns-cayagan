<?php
namespace App\Domains\Leader\Http\Controllers\Api;

use App\Domains\Auth\Http\Controllers\Api\Traits\UserMisc;
use App\Domains\Auth\Http\Requests\Backend\User\EditUserRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Candidate\Models\Candidate;
use App\Domains\Leader\Actions\UpdateLeaderAction;
use App\Domains\Leader\Http\Resources\LeaderResource;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\UpdateModelAddressAction;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class LeaderController
{
    use ApiResponseHelpers;
    use UserMisc;

    public function index(User $leader)
    {
        return  $this->respondCreated([
            'leader' => new LeaderResource($leader),
            'address' => $this->address($leader)
        ]);
    }

    public function update(EditUserRequest $request, User $leader)
    {
        $leader = (new UpdateLeaderAction)($request->data(), $leader);
        $address = (new UpdateModelAddressAction)($request->data()->address, $leader);

        return  $this->respondCreated([
            'leader' => new LeaderResource($leader),
            'address' => new AddressResource($address)
        ]);
    }

    public function delete(User $leader)
    {
        $leader->delete();

        return  $this->respondCreated([
            'leader' => new LeaderResource($leader),
            'address' => $this->address($leader)
        ]);
    }

    public function list($referralBy)
    {

        // if($referralBy)
        // {
        //     $leaders = $this->leaders($referralBy);

        // } else {
        //     $leaders = Leader::join('users', 'users.id', 'leaders.user_id')
        //         ->select('leaders.*', 'users.*')
        //         ->get();
        // }

        // return $this->respondWithSuccess([
        //     'number_leaders' => count($leaders),
        //     'leaders' => $leaders
        // ]);

    }

    public function search($referredBy, $keyword)
    {
        return $this->searchUser($keyword, $referredBy, 'leaders');
    }

    public function storeMessages()
    {

    }

    public function storeContacts(Request $request)
    {
        dd($request->all());
    }

    public function getPurokLeaders(Barangay $barangay)
    {
        $addresses = Address::where('addressable_type', Leader::class)
            ->where('barangay_code', $barangay->barangay_code)->get();
        $leadersList = collect();
        foreach($addresses as $address) {
            $leader = Leader::find($address->addressable_id);
            if($leader && $address->zone !== null) {
                $leadersList->push($this->leader($leader->user, $address));
            }
         }

         return $this->respondWithSuccess([
            'number_leader' => count($leadersList),
            'leaders' => $leadersList
        ]);
    }
}
