<?php
namespace App\Domains\Leader\Http\Controllers\Backend;

use App\Domains\Auth\Http\Controllers\Api\Traits\UserMisc;
use App\Domains\Auth\Http\Requests\Backend\User\EditUserRequest;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use App\Domains\Candidate\Models\Candidate;
use App\Domains\Leader\Actions\UpdateLeaderAction;
use App\Domains\Leader\Http\Resources\LeaderResource;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\Actions\UpdateModelAddressAction;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Leader\Http\Requests\RegisterLeaderRequest;
use App\Domains\Messages\Actions\ProcessMessageAction;
use App\Domains\Messages\Actions\ProcessSendCredentialAction;
use App\Domains\Messages\Models\Message;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderController
{
    use ApiResponseHelpers;
    use UserMisc;

    protected $userService;
    private $locationBase = [
        'region' => null,
        'province' => null,
        'city' => null,
        'barangay' => null
    ];

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(User $leader)
    {
        return view('backend.leader.index');
    }

    public function location()
    {
        return view('backend.leader.location');
    }

    public function store(RegisterLeaderRequest $request)
    {
        $input = $request->validated();
        $user = User::find($input['user_id']);
        $user->phone = $input['phone'];
        $userWithSelectPhone = User::where('phone', $input['phone'])->first();
        if ($userWithSelectPhone) {
            if($user->id !== $userWithSelectPhone->id) {
                return redirect()->back()->withErrors('Phone Number is already existing');
            }

        }
        $user->save();
        $user->refresh();
        $barangay = Barangay::where('barangay_code', $input['barangay'])->first();
        $input['city'] = $input['city'] ?? $barangay->city_municipality_code;
        $input['province'] = $input['province'] ?? $barangay->province_code;
        $input['region'] = $input['region'] ?? $barangay->region_code;
        $existingLeader = $this->checkExistingLeaderOnLocation($input);

        try {
            if($existingLeader) {
                $this->updateLeader($user, $input, $existingLeader);
                return redirect()->back()->withFlashSuccess('Leader Successfully Changed!');
            }
            $this->addLeader($user, $input);
            return redirect()->back()->withFlashSuccess('Leader Created');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    private function sendAccountDetails($leader)
    {
        $phone = $leader->user->phone ;
        if($phone === null || $phone === '') {
            $phone = '00000000000';
        }

        if(auth()->user()->address->province_code) {
            $sender = 'AJPONCE';
        } else {
            $sender = 'AREZA';
        }

        $content = "Welcome " . $leader->user->phone ." to EKNS. Your Account Information is  Username: $phone Password: $phone";
        // (new ProcessSendCredentialAction)($sender, $content, $leader);
    }

    private function checkExistingLeaderOnLocation($input)
    {
        $leaderCountInALocation = Address::where('zone', $input['zone'])
            ->where('barangay_code', $input['barangay'])
            ->where('city_code', $input['city'])
            ->where('province_code', $input['province'])
            ->where('region_code', $input['region'])
            ->where('addressable_type', Leader::class)->first();
        return ($leaderCountInALocation) ? $leaderCountInALocation->addressable_id : false;
    }

    private function updateLeader($user, $input, $existingLeader)
    {
        $leaderToBeDeleted = Leader::find($existingLeader);
        $addressOfExistingLeader = Address::where('addressable_id', $existingLeader)
            ->where('addressable_type', Leader::class)->first();

        if ($leaderToBeDeleted) {
            $this->userService->updatePassword($leaderToBeDeleted->user, ['password' =>  'deleted']);
            $leaderToBeDeleted->delete();
        }

        $leader = Leader::create([
            'user_id' => $user->id,
        ]);

        $addressOfExistingLeader->addressable_id = $leader->id;
        $addressOfExistingLeader->save();
        $this->userService->updatePassword($user, ['password' =>  $user->phone]);
        $this->setLeaderRole($leader->user, $input);
        $this->sendAccountDetails($leader);

    }

    private function setLeaderRole($user, $input) {

        $role = Role::where('name', 'Barangay Leader')->first();
        if ($input['zone'] !== null) {
            $role = Role::where('name', 'Purok Leader')->first();
        }

        $user->syncRoles([$role->id] ?? []);

    }

    private function addLeaderAddress($leader, $input)
    {
        Address::create([
            'addressable_type' => Leader::class,
            'addressable_id'   => $leader->id,
            'zone'             => $input['zone'],
            'barangay_code'    => $input['barangay'],
            'city_code'        => $input['city'],
            'province_code'    => $input['province'],
            'region_code'      => $input['region']
        ]);
    }

    private function addLeader($user, $input)
    {
        $leader = Leader::create([
            'user_id' => $user->id,
        ]);

        $this->userService->updatePassword($user, ['password' =>  $user->phone]);
        $this->setLeaderRole($leader->user, $input);
        $this->addLeaderAddress($leader, $input);
        $this->sendAccountDetails($leader);
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

    public function show(Leader $leader)
    {
        $user = User::where('id', $leader->user_id)->first();

        return view('backend.leader.show')
            ->with('user', $user)
            ->with('leader', $leader);
    }

    public function delete(User $leader)
    {
        $leader->delete();

        return  $this->respondCreated([
            'leader' => new LeaderResource($leader),
            'address' => $this->address($leader)
        ]);
    }

    public function list(Request $request)
    {

        $leaders = Leader::join('users', 'users.id', 'leaders.user_id')
            ->join('addresses', 'addresses.addressable_id', 'leaders.id');

        foreach($request->get('code') as $key => $location)
        {
            $leaders->where('addresses.' . $key, $location);
        }

        $leaders->select('users.id', DB::raw("CONCAT(first_name, ' ' , middle_name , ' ', last_name) as name"));
        return json_encode($leaders->get(), true);

    }

    public function search($referred_by, $keyword)
    {
        return $this->searchUser($keyword, $referred_by, 'leaders');
    }
}
