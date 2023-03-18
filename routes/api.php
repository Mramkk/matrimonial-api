<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\EducationDetailsController;
use App\Http\Controllers\Api\FamilyDetailsController;
use App\Http\Controllers\Api\HabitsController;
use App\Http\Controllers\Api\HoroscopeDetailsController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\MotherTongueController;
use App\Http\Controllers\Api\OccupationController;
use App\Http\Controllers\Api\OccupationDetailsController;
use App\Http\Controllers\Api\PersonalDetailController;
use App\Http\Controllers\Api\PhysicalDetailsController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\ShortlitController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::prefix('religion')->group(function () {
//     Route::controller(ReligionController::class)->group(function () {
//         Route::post('/save', 'save');
//     });
//     Route::controller(ReligionController::class)->group(function () {
//         Route::get('/data', 'data');
//     });
// });

Route::controller(ReligionController::class)->group(function () {
    Route::post('/religion', 'religion');
    Route::get('/religion', 'religion');
});
Route::controller(CountryController::class)->group(function () {
    Route::post('/country', 'country');
    Route::get('/country', 'country');
});
Route::controller(MotherTongueController::class)->group(function () {
    Route::post('/mother-tongue', 'motherTongue');
    Route::get('/mother-tongue', 'motherTongue');
});
Route::controller(EducationController::class)->group(function () {
    Route::post('/education', 'education');
    Route::get('/education', 'education');
});
Route::controller(DegreeController::class)->group(function () {
    Route::post('/degree', 'degree');
    Route::get('/degree', 'degree');
});
Route::controller(OccupationController::class)->group(function () {
    Route::post('/occupation', 'occupation');
    Route::get('/occupation', 'occupation');
});




Route::controller(UserController::class)->group(function () {
    Route::post('/user', 'user');
    Route::get('/user', 'user');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/logout', [UserController::class, "logout"]);
    Route::get('/user/data', [UserController::class, "data"]);
    Route::get('/user/list', [UserController::class, "userList"]);
    Route::post('/user/update', [UserController::class, "update"]);
    Route::post('/user/upload-id-proof', [UserController::class, "uploadIdProof"]);
    Route::post('/user/upload-profile-img', [UserController::class, "uploadProfileImg"]);

    Route::controller(ShortlitController::class)->group(function () {
        Route::post('/shortlist', 'shortlist');
        Route::get('/shortlist', 'shortlist');
    });
    Route::controller(InterestController::class)->group(function () {
        Route::post('/interest', 'interest');
        Route::get('/interest', 'interest');
    });
});
