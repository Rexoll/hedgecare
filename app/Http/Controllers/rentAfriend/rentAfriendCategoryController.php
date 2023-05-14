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
     * @return \Illuminate\Http\JsonResponse
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
}