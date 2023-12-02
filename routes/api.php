<?php

use App\Helper\ApiRes;
use App\Http\Controllers\Api\Country\ApiCountryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\MotherTongueController;
use App\Http\Controllers\Api\OccupationController;
use App\Http\Controllers\Api\PartnerPreferencesController;
use App\Http\Controllers\Api\Preference\ApiPreferenceController;
use App\Http\Controllers\Api\Preference\ApiPreferenceCountryController;
use App\Http\Controllers\Api\Profile\ApiProfileController;
use App\Http\Controllers\Api\Religion\ApiReligionController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\ShortlitController;
use App\Http\Controllers\Api\User\ApiUserController;
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
Route::controller(ApiReligionController::class)->group(function () {
    Route::any('/religion', 'data');
    Route::any('/religion/save', 'save');
});
// Route::controller(ReligionController::class)->group(function () {
//     Route::any('/religion', 'religion');
// });
Route::controller(ApiCountryController::class)->group(function () {
    Route::any('/country', 'country');
    Route::any('/state', 'state');
    Route::any('/city', 'city');
});

Route::controller(ApiPreferenceCountryController::class)->group(function () {
    Route::any('/preference/state', 'state');
    Route::any('/preference/city', 'city');
});
// Route::controller(CountryController::class)->group(function () {
//     Route::any('/country', 'country');
// });

Route::controller(MotherTongueController::class)->group(function () {
    Route::any('/mother-tongue', 'data');
    Route::any('/mother-tongue/save', 'save');
});
// Route::controller(MotherTongueController::class)->group(function () {
//     Route::any('/mother-tongue', 'motherTongue');
// });
Route::controller(EducationController::class)->group(function () {
    Route::any('/education', 'education');
});
Route::controller(DegreeController::class)->group(function () {
    Route::any('/degree', 'degree');
});
Route::controller(OccupationController::class)->group(function () {
    Route::any('/occupation', 'occupation');
});



// Route::controller(UserController::class)->group(function () {
//     Route::any('/user', 'user');
// });
// Route::controller(CountryController::class)->group(function () {
//     Route::any('/country', 'country');
// });

Route::controller(ApiUserController::class)->group(function () {
    Route::any('/user/register', 'register');
    Route::any('/user/otp/send', 'sendOTP');
    Route::any('/user/otp/verify', 'verifyOTP');
    // OTP For Login
    Route::any('/user/login/send/otp', 'sendOTPLogin');
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::post('/user/logout', [UserController::class, "logout"]);
    // Route::get('/user/data', [UserController::class, "data"]);
    // Route::get('/user/list', [UserController::class, "userList"]);
    // Route::get('/user/search', [UserController::class, "search"]);
    // Route::get('/user/matches', [UserController::class, "myMatches"]);
    // Route::get('/user/near-me', [UserController::class, "nearMe"]);
    // Route::post('/user/update', [UserController::class, "update"]);
    // Route::post('/user/partial-update', [UserController::class, "partialUpdate"]);
    // Route::post('/user/upload-id-proof', [UserController::class, "uploadIdProof"]);
    // Route::post('/user/upload-profile-img', [UserController::class, "uploadProfileImg"]);

    // Route::any('/user/image', [UserController::class, "UserImage"]);

    Route::controller(ApiProfileController::class)->group(function () {
        Route::any('/profile/data', 'data');
        Route::any('/profile/step/one', 'buildProfileStepOne');
        Route::any('/profile/acc', 'accDetails');
        Route::any('/profile/personal', 'personal');
        Route::any('/profile/physical', 'physical');
        Route::any('/profile/edu', 'edu');
        Route::any('/profile/habits', 'habits');
        Route::any('/profile/family', 'family');
        Route::any('/profile/about', 'about');
        Route::any('/profile/image', 'uploadImg');
        Route::any('/profile/document', 'uploadDocument');
    });

    Route::controller(ApiPreferenceController::class)->group(function () {
        Route::any('/preference/basic', 'basic');
        Route::any('/preference/religion', 'religion');
        Route::any('/preference/location', 'location');
        Route::any('/preference/edu', 'edu');
        Route::any('/preference/expectation', 'expectation');
    });


    Route::controller(ShortlitController::class)->group(function () {
        Route::any('/shortlist', 'shortlist');
    });

    Route::controller(PartnerPreferencesController::class)->group(function () {
        Route::any('/partner-preference', 'partnerPreference');
    });

    Route::controller(VisitedProfileController::class)->group(function () {
        Route::any('/visited-profile', 'visitedProfile');
    });

    Route::controller(InterestController::class)->group(function () {
        Route::any('/interest', 'interest');
    });
});
