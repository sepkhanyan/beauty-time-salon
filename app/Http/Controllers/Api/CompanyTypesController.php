<?php

namespace App\Http\Controllers\Api;

use App\Laravue\JsonResponse;
use App\Laravue\Models\CompanyType;

class CompanyTypesController extends BaseController
{
    /**
     * Display categories list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $companyTypes = CompanyType::where('archived', 0)->get();
        return response()->json(new JsonResponse(['types' => $companyTypes, 'total' => count($companyTypes)]));
    }
}
