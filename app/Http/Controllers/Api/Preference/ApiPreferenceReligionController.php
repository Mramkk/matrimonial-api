<?php

namespace App\Http\Controllers\Api\Preference;

use App\Helper\ApiRes;
use App\Http\Controllers\Controller;
use App\Models\Religion;
use Illuminate\Http\Request;

class ApiPreferenceReligionController extends Controller
{
    public function community(Request $req)
    {
        $data = Religion::whereIn('religion', $req->religion)->get();
        if ($data) {
            return ApiRes::data("Community List", $data);
        } else {
            return ApiRes::error();
        }
    }
}
