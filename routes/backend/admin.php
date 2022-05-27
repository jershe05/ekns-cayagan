<?php

use App\Domains\Analytics\Http\Controllers\VotersAnalyticsController;
use App\Domains\Files\Http\Controllers\FileController;
use App\Domains\Household\Http\Controllers\HouseholdController;
use App\Domains\Leader\Http\Controllers\Backend\LeaderController;
use App\Domains\Messages\Http\Controllers\MessageController;
use App\Domains\PhoneLogs\Http\Controllers\Api\PhoneLogsController;
use App\Domains\Voter\Http\Controllers\Backend\VoterController;
use App\Http\Controllers\Backend\DashboardController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });
Route::middleware(['auth'])->group(function () {

    Route::get('voters', [VoterController::class, 'index'])->name('voters.index');

    Route::post('total/voters/store', [VotersAnalyticsController::class, 'storeTotalVoters'])->name('total.voters.store');
    Route::get('total/voters', [VotersAnalyticsController::class, 'totalVoters'])->name('total.voters');

    Route::get('leaders', [LeaderController::class, 'index'])->name('leaders.index');
    Route::post('leader', [LeaderController::class, 'store'])->name('leader.store');
    Route::get('leader/show/{leader}', [LeaderController::class, 'show'])->name('leader.show');
    Route::get('leaders/location', [LeaderController::class, 'location'])->name('leaders.location');

    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/history', [MessageController::class, 'history'])->name('messages.history');

    Route::get('upload-file', [PhoneLogsController::class, 'fileUpload']);

    Route::post('upload-file', [PhoneLogsController::class, 'storeImage'])->name('image.store');
    Route::get('image/show/{file}', [FileController::class, 'show'])->name('image.show');

    Route::post('leaders/list', [LeaderController::class, 'list'])->name('leaders.list');
    Route::post('import-voters', [VoterController::class, 'importVotersFromExcel'])->name('import.voter');
    Route::post('upload-excel', [VoterController::class, 'uploadExcel']);

    Route::delete('household/delete/{household}', [HouseholdController::class, 'delete'])->name('household.delete');
    Route::get('household', [HouseholdController::class, 'index'])->name('household.index');

    Route::get('tagging',[VoterController::class, 'tagging']);
});

