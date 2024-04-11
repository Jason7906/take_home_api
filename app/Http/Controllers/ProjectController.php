<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // 找出建案類型為大樓且管理員部門為資料部的建案
        $projects = Project::where('type', '大樓')
                            ->where('manager_department', '資料部')
                            ->get();

        // 計算各別建案最高交易單價
        $result = $projects->map(function ($project) {
            $maxPrice = $project->transactions()->max('price');
            return [
                'project_name' => $project->name,
                'highest_price' => $maxPrice
            ];
        });

        return response()->json($result);
    }
}