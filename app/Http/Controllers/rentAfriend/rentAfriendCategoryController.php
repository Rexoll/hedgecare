<?php

namespace App\Http\Controllers\rentAfriend;

use App\Http\Controllers\Controller;
use App\Models\rentAfriendCategory;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class rentAfriendCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $get = rentAfriendCategory::with('services')->get();
            return response()->json([
                'data' => $get
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
     * @param  \App\Models\rentAfriendCategory  $rentAfriendCategory
     * @return \Illuminate\Http\Response
     */
    public function show(rentAfriendCategory $rentAfriendCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rentAfriendCategory  $rentAfriendCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rentAfriendCategory $rentAfriendCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rentAfriendCategory  $rentAfriendCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(rentAfriendCategory $rentAfriendCategory)
    {
        //
    }
}
