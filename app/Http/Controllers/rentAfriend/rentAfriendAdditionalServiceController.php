<?php

namespace App\Http\Controllers\rentAfriend;

use App\Http\Controllers\Controller;
use App\Models\rentAfriendAdditionalService;
use Illuminate\Http\Request;

class rentAfriendAdditionalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $get = rentAfriendAdditionalService::with('category','skill')->get();
            return response()->json([
                'data' => $get,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
