<?php

use App\Domains\Leader\Http\Controllers\Api\LeaderController;
use App\Domains\Voter\Http\Controllers\Api\VoterController;
use App\Domains\Auth\Http\Controllers\Api\User\UserController;
use App\Domains\Candidate\Http\Controllers\CandidateController;
use App\Domains\Files\Http\Controllers\FileController;
use App\Domains\Household\Http\Controllers\Api\HouseholdApiController;
use App\Domains\Messages\Http\Controllers\Api\MessageController;
use App\Domains\Misc\Http\Controllers\API\PositionController;
use App\Domains\Misc\Http\Controllers\API\RolesController;
use App\Domains\Misc\Http\Controllers\API\ScopeController;
use App\Domains\PhoneLogs\Http\Controllers\Api\PhoneLogsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Voter
Route::post('voter',[VoterController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:admin.create.voter']);

Route::post('voter/confirm',[VoterController::class, 'confirm'])
    ->middleware(['auth:sanctum', 'ability:admin.update.voter']);

Route::get('voter/{voter}',[VoterController::class, 'index'])
    ->middleware(['auth:sanctum', 'ability:admin.view.voter']);

Route::put('voter/{voter}',[VoterController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:admin.update.voter']);

Route::delete('voter/{voter}',[VoterController::class, 'delete'])
    ->middleware(['auth:sanctum', 'ability:admin.delete.voter']);

Route::get('voters/{household}/{leader}',[VoterController::class, 'list']);
    // ->middleware(['auth:sanctum', 'ability:admin.list.voter']);

Route::post('voters/set/stance',[VoterController::class, 'setVoterStance']);
    // ->middleware(['auth:sanctum', 'ability:admin.list.voter']);

Route::get('self/registered/voters/{leader}',[VoterController::class, 'selfRegisteredVoters'])
    ->middleware(['auth:sanctum', 'ability:admin.list.voter']);

Route::get('search/voters/{keyword}',[VoterController::class, 'search'])
    ->middleware(['auth:sanctum', 'ability:admin.list.voter']);

//Leader

Route::post('leader',[UserController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:admin.create.leader']);

Route::get('leader/{leader}',[LeaderController::class, 'index'])
    ->middleware(['auth:sanctum', 'ability:admin.view.leader']);

Route::put('leader/{leader}',[LeaderController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:admin.update.leader']);

Route::delete('leader/{leader}',[LeaderController::class, 'delete'])
    ->middleware(['auth:sanctum', 'ability:admin.delete.leader']);

Route::get('leaders/{referral_by}',[LeaderController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:admin.list.leader']);

Route::get('leader/messages',[MessageController::class, 'getNewMessages'])
    ->middleware(['auth:sanctum', 'ability:admin.list.leader']);

Route::get('search/leaders/{referred_by}/{keyword}',[LeaderController::class, 'search'])
    ->middleware(['auth:sanctum', 'ability:admin.list.leader']);

//Candidate

Route::post('register/candidate',[CandidateController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:admin.create.candidate']);

Route::post('register/candidates',[CandidateController::class, 'list'])
    ->middleware(['auth:sanctum', 'ability:admin.list.candidate']);

    //roles

Route::middleware(['auth:sanctum', 'ability:admin.access.roles'])->group(function(){
    Route::post('roles',[RolesController::class, 'index']);
});

//Misc
//scopes

Route::middleware('auth:sanctum')->group(function(){
    Route::get('scopes',[ScopeController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('positions',[PositionController::class, 'index']);
});


Route::post('candidates/{referral_code}/dashboard',[CandidateController::class, 'dashboard']);

Route::post('phone/messages',[PhoneLogsController::class, 'storeMessages'] );
Route::post('phone/contacts',[PhoneLogsController::class, 'storeContacts'] );

Route::post('image', [FileController::class, 'store']);

Route::fallback(function(){
    return response()->json([
        'message' => 'bad request'], 404);
});


//household

Route::get('household-count/{user}',[HouseholdApiController::class, 'getHouseholdCount']);
Route::post('household',[HouseholdApiController::class, 'addHousehold']);
Route::get('households/{user}',[HouseholdApiController::class, 'list']);
Route::get('barangay/households/{barangay}',[HouseholdApiController::class, 'barangayHouseholdList']);
Route::get('household-address/{household}',[HouseholdApiController::class, 'getAddress']);
Route::post('add/household/voter',[HouseholdApiController::class, 'addVoter']);
Route::delete('remove/{voter}/household',[HouseholdApiController::class, 'removeVoter']);
Route::get('voters-barangay/{barangay}/{keyword}',[VoterController::class, 'listOfVotersFromBarangay']);
Route::get('purok/leaders/{barangay}',[LeaderController::class, 'getPurokLeaders']);

