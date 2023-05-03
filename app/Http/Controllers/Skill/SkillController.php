<?php

namespace App\Http\Controllers\Skill;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->query("search", "");
            $skills = Skill::where([
                ["name", "LIKE", "%" . $search . "%"]
            ])->get();
            return response()->json([
                "message" => "success get skills",
                "data" => $skills,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}