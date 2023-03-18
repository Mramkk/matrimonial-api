<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\MotherTongueController;
use App\Http\Controllers\Api\OccupationController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\ShortlitController;
use App\Http\Controllers\Api\VisitedProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


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
    Route::any('/religion', 'religion');
});
Route::controller(CountryController::class)->group(function () {
    Route::any('/country', 'country');
});
Route::controller(MotherTongueController::class)->group(function () {
    Route::any('/mother-tongue', 'motherTongue');
});
Route::controller(EducationController::class)->group(function () {
    Route::any('/education', 'education');
});
Route::controller(DegreeController::class)->group(function () {
    Route::any('/degree', 'degree');
});
Route::controller(OccupationController::class)->group(function () {
    Route::any('/occupation', 'occupation');
});



Route::controller(UserController::class)->group(function () {
    Route::any('/user', 'user');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/logout', [UserController::class, "logout"]);
    Route::get('/user/data', [UserController::class, "data"]);
    Route::get('/user/list', [UserController::class, "userList"]);
    Route::post('/user/update', [UserController::class, "update"]);
    Route::post('/user/partial-update', [UserController::class, "partialUpdate"]);
    Route::post('/user/upload-id-proof', [UserController::class, "uploadIdProof"]);
    Route::post('/user/upload-profile-img', [UserController::class, "uploadProfileImg"]);

    Route::controller(ShortlitController::class)->group(function () {
        Route::any('/shortlist', 'shortlist');
    });

    Route::controller(VisitedProfileController::class)->group(function () {
        Route::any('/visited-profile', 'visitedProfile');
    });

    Route::controller(InterestController::class)->group(function () {
        Route::any('/interest', 'interest');
    });
});
