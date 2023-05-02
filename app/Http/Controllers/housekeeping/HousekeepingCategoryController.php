<?php

namespace App\Http\Controllers\housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingCategory;
use Illuminate\Http\Request;

class HousekeepingCategoryController extends Controller
{
    public function index()
    {
        $housekeeping_categories = HousekeepingCategory::with("services")->get();
        if ($housekeeping_categories->isEmpty()) {
            return response()->json([
                "message" => "housekeeping categories is empty"
            ], 404);
        }
        return response()->json([
            "message" => "success get housekeeping categories",
            "data" => array_map(function (array $value) {
                return [...$value, "thumbnail" => env("APP_URL") . $value["thumbnail"]];
            }, $housekeeping_categories->toArray()),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}