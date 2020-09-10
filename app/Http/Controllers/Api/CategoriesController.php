<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display categories list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request['company_type_id'];
        $categories = Category::whereHas('types', function ($query) use ($type){
            $query->where('type_id', $type);
        })->orderBy('sort_id', 'ASC')->get();
        return response()->json(new JsonResponse(['categories' => $categories, 'total' => count($categories)]));
    }
}
