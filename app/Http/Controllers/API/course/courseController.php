<?php

namespace App\Http\Controllers\API\course;

use App\Http\Controllers\Controller;
use App\Models\course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class courseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $get = course::with('user')->get();
            return response()->json([
                'date' => $get,
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
        try {
            $validate = Validator::make($request->all(), [
                'user_id' => 'required',
                'course' => 'required',
                'price' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $create = course::create([
                    'user_id' => $request->user_id,
                    'course' => $request->course,
                    'price' => $request->price,
                ]);
                return response()->json([
                    'data' => $create,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $get = course::findOrFail($id)
                ->with('user')
                ->first();
            return response()->json([
                'date' => $get,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'user_id' => 'required',
                'course' => 'required',
                'price' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $course = course::findOrFail($id);
                $course->update([
                    'user_id' => $request->user_id,
                    'course' => $request->course,
                    'price' => $request->price,
                ]);
                return response()->json([
                    'message' => 'course updated to ' . $course->course,
                    'data' => $course,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            course::findOrFail($id)->delete();
            return response()->json([
                'message' => 'deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
