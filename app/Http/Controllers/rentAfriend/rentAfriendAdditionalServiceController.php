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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $get = rentAfriendAdditionalService::with('category')->get();
            return response()->json([
                'data' => $get,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\rentAfriendAdditionalService  $rentAfriendAdditionalService
     * @return \Illuminate\Http\Response
     */
    public function show(rentAfriendAdditionalService $rentAfriendAdditionalService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rentAfriendAdditionalService  $rentAfriendAdditionalService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rentAfriendAdditionalService $rentAfriendAdditionalService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rentAfriendAdditionalService  $rentAfriendAdditionalService
     * @return \Illuminate\Http\Response
     */
    public function destroy(rentAfriendAdditionalService $rentAfriendAdditionalService)
    {
        //
    }
}
