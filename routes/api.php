<?php

use App\Helper\ApiRes;
use App\Http\Controllers\Api\Country\ApiCountryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\Interest\ApiInterestController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\Member\ApiMemberController;
use App\Http\Controllers\Api\MotherTongueController;
use App\Http\Controllers\Api\MyMatches\ApiMyMatchesController;
use App\Http\Controllers\Api\NearMe\ApiNearMeController;
use App\Http\Controllers\Api\OccupationController;
use App\Http\Controllers\Api\PartnerPreferencesController;
use App\Http\Controllers\Api\Preference\ApiPreferenceController;
use App\Http\Controllers\Api\Preference\ApiPreferenceCountryController;
use App\Http\Controllers\Api\Preference\ApiPreferenceReligionController;
use App\Http\Controllers\Api\Profile\ApiProfileController;
use App\Http\Controllers\Api\Religion\ApiReligionController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\Search\ApiSearchController;
use App\Http\Controllers\Api\Shortlist\ApiShortlistController;
use App\Http\Controllers\Api\ShortlitController;
use App\Http\Controllers\Api\User\ApiUserController;
use App\Http\Controllers\Api\VisitedProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\View\ApiViewController;
use App\Http\Controllers\Api\Viewed\ApiViewedController;
use App\Http\Controllers\Api\Visited\ApiVisitedController;
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
Route::controller(ApiPreferenceReligionController::class)->group(function () {
    Route::any('/preference/religion/community', 'community');
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
    Route::any('/user/login', 'login');
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

    Route::controller(ApiUserController::class)->group(function () {
        Route::any('/user', 'data');
        Route::any('/user/by-id', 'byId');
        Route::any('/user/password/reset', 'passwordReset');
        Route::any('/user/logout', 'logout');
    });
    Route::controller(ApiMemberController::class)->group(function () {
        Route::any('/member', 'data');
    });

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
        Route::any('/preference', 'data');
        Route::any('/preference/basic', 'basic');
        Route::any('/preference/religion', 'religion');
        Route::any('/preference/location', 'location');
        Route::any('/preference/edu', 'edu');
        Route::any('/preference/expectation', 'expectation');
    });


    // Route::controller(ShortlitController::class)->group(function () {
    //     Route::any('/shortlist', 'shortlist');
    // });
    Route::controller(ApiShortlistController::class)->group(function () {
        Route::any('/shortlist/data', 'data');
        Route::any('/shortlist/save', 'save');
        Route::any('/shortlist/delete', 'delete');
    });

    Route::controller(PartnerPreferencesController::class)->group(function () {
        Route::any('/partner-preference', 'partnerPreference');
    });

    // Route::controller(VisitedProfileController::class)->group(function () {
    //     Route::any('/visited-profile', 'visitedProfile');
    // });
    Route::controller(ApiVisitedController::class)->group(function () {
        Route::any('/visited/data', 'data');
        Route::any('/visited/save', 'save');
        Route::any('/visited/delete', 'delete');
    });

    Route::controller(ApiViewedController::class)->group(function () {
        Route::any('/viewed/data', 'data');
        Route::any('/viewed/save', 'save');
        Route::any('/viewed/delete', 'delete');
    });

    // Route::controller(InterestController::class)->group(function () {
    //     Route::any('/interest', 'interest');
    // });
    Route::controller(ApiInterestController::class)->group(function () {
        Route::any('/interest/data', 'data');
        Route::any('/interest/save', 'save');
        Route::any('/interest/delete', 'delete');
    });
    Route::controller(ApiNearMeController::class)->group(function () {
        Route::any('/nearme', 'data');
    });
    Route::controller(ApiMyMatchesController::class)->group(function () {
        Route::any('/mymatches', 'data');
    });
    Route::controller(ApiSearchController::class)->group(function () {
        Route::any('/search', 'data');
        Route::any('/search-by', 'dataBy');
    });
});
