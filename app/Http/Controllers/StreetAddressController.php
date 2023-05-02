<?php

namespace App\Http\Controllers;

use App\Models\StreetAddress;
use Illuminate\Http\Request;
use Validator;

class StreetAddressController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query("search", "");
        $street_addresses = StreetAddress::where([
            ["country_code", "=", "CA"],
            ["name", "LIKE", "%" . $search . "%"],
        ])->get();
        return response()->json([
            "message" => $search != "" ? "success search " . $search . " street addresses" : "success get all street address",
            "data" => $street_addresses,
        ], 200);
    }
}