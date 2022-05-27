<?php
namespace App\Domains\Candidate\Http\Controllers;

use App\Domains\Auth\Http\Controllers\Api\Traits\UserMisc;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\ApiUserService;
use App\Domains\Candidate\Http\Requests\StoreCandidateRequests;
use App\Domains\Candidate\Http\Resources\CandidateResource;
use App\Domains\Candidate\Models\Candidate;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use F9Web\ApiResponseHelpers;

class CandidateController
{
    use ApiResponseHelpers;
    use UserMisc;
    private $userService;
    public function __construct(ApiUserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreCandidateRequests $request)
    {
        $input = $request->validated();
        $user = $this->userService->store($input);
        $candidate = Candidate::create([
            'user_id' => $user->id,
            'position_id' => $input['position_id'],
            'scope_id' => $input['scope_id'],
            'referral_code' => $this->generateReferralCode()
        ]);

        (new StoreModelAddressAction)($request->data()->address, $user);
        $user->refresh();
        $result = [
            'user' => new CandidateResource($user),
            'address' => $this->address($user),
            'permissions' => $this->permissions($user),
            'scope' => $this->getScopeName($candidate),
            'position' => $this->getPositionName($candidate)
        ];
        return $this->respondWithSuccess($result);
    }

    public function list()
    {
        $candidates = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('candidates', 'candidates.user_id', '=', 'users.id')
            ->where('roles.name', 'Candidate')
            ->get();

        if($candidates){
            return $this->respondWithSuccess([
                'number_leaders' => count($candidates),
                'leaders' => $candidates
            ]);
        }

        return $this->respondWithSuccess([
            'number_leaders' => count($candidates),
            'leaders' => $candidates
        ]);

    }

    public function dashboard($referralCode)
    {
        $leaders = $this->leaders($referralCode);

        $voters = 0;
        foreach($leaders as $leader)
        {
            $voters += User::where('added_by', $leader->id)->count();
        }

        return $this->respondWithSuccess([
            'number_leaders' => $leaders->count(),
            'number_voters' => $voters
        ]);
    }

}
