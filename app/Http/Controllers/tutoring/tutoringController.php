<?php

namespace App\Http\Controllers\tutoring;

use App\Http\Controllers\Controller;
use App\Models\selected_course;
use App\Models\tutoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class tutoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $get = tutoring::with('course')->get();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                '.*.order_type' => 'required',
                '.*.environment' => 'required|in:individual,group_lessons',
                '.*.skill_id' => 'required',
                '.*.provider_id' => 'required',
                '.*.duration' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $create = tutoring::create([
                    'order_type' => $request->order_type,
                    'environment' => $request->environment,
                    'duration' => $request->duration,
                ]);
                foreach ($request['tutoring'] as $value) {
                    $tutorings = new selected_course();
                    $tutorings->tutoring_id = $create->id;
                    $tutorings->provider_id = $value['provider_id'];
                    $tutorings->skill_id = $value['skill_id'];
                    $tutorings->save();
                }
                $result = tutoring::where('id', $create->id)
                    ->with('skill')
                    ->get();
                return response()->json([
                    'data' => $result,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tutoring  $tutoring
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $get = tutoring::where('id', $id)
                ->with('course')
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
     * @param  \App\Models\tutoring  $tutoring
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'registration_type' => 'required',
                'course' => 'required',
                'date' => 'required',
                'hours' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => $validate->errors(),
                ], 400);
            } else {
                $update = tutoring::findOrFail($id);
                $update->update([
                    'registration_type' => $request->registration_type,
                    'class' => $request->class,
                    'date' => $request->date,
                    'hours' => $request->hours,
                    'course' => $request->course,
                ]);
                return response()->json([
                    'message' => 'tutoring updated',
                    'data' => $update,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tutoring  $tutoring
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            tutoring::findOrFail($id)->delete();
            return response()->json([
                'message' => 'deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}