<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Laravue\JsonResponse;
use App\Laravue\Models\CategoryService;
use Illuminate\Http\Request;

class CategoryServicesController extends Controller
{
    /**
     * Display company types list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $services = CategoryService::whereCategoryId($request['category_id'])
            ->orderBy('sort_id', 'ASC')->get();
        return response()->json(new JsonResponse(['services' => $services]));
    }
}
