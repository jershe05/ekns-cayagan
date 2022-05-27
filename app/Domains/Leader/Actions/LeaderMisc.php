<?php
namespace App\Domains\Leader\Actions;

use App\Domains\Leader\Http\Requests\RegisterLeaderRequest;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use Illuminate\Support\Facades\DB;

/**
 *
 */
trait LeaderMisc
{
    protected function storeLeader($request)
    {
        $input = $request->validated();
        $input['password'] = $input['username'];

        try {
            $user = $this->userService->store($input);

            (new StoreModelAddressAction)($request->address(), $user);

            $leader = Leader::create([
                'user_id' => $user->id,
                'scope_id' => $request->scope()->scope_id,
                'referred_by' => $input['referred_by'],
                'candidate_id' => $input['candidate_id'],
                'organization_id' => $input['organization_id'],
            ]);

            (new StoreModelAddressAction)($request->leaderAddress(), $leader);
            return [
                'result' => true,
                'message' => 'Leader Added.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'result' => false,
                'message' => $e
            ];
        }
    }
}

