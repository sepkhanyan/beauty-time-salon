<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSalonCategoryRequest;
use App\Laravue\JsonResponse;
use App\Laravue\Models\SalonCategory;
use Illuminate\Http\Request;

class SalonCategoriesController extends Controller
{
    /**
     * Display salon categories list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = SalonCategory::where('salon_id', $request['salon_id'])
            ->with('category')
            ->orderBy('sort_id', 'ASC');
        $categories = $categories->title($request['title']);
        $categories = $categories->get();
        return response()->json(new JsonResponse(['categories' => $categories, 'total' => count($categories)]));
    }

    /**
     * Store a newly created salon category in storage.
     *
     * @param  \App\Http\Requests\UpdateSalonCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateSalonCategoryRequest $request)
    {
        $data = $request->all();
        $salonCategory = SalonCategory::create($data);
        $salonCategory->load('category');
        return response()->json(new JsonResponse(['category' => $salonCategory]));
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalonCategoryRequest  $request
     * @param  $salonCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalonCategoryRequest $request, SalonCategory $salonCategory)
    {
        $data = $request->all();
        $salonCategory->update($data);
        $salonCategory->load('category');
        return response()->json(new JsonResponse(['category' => $salonCategory]));
    }

    /**
     * Remove the specified salon category from storage.
     *
     * @param  $salonCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalonCategory $salonCategory)
    {
        $salonCategory->delete();
        return response()->json(new JsonResponse(['status' => 'success']));
    }

    /**
     * Salon categories sorting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request)
    {
        $data = $request->all();
        foreach ($data as $key => $value){
            SalonCategory::whereId($value)->update(['sort_id' => $key + 1]);
        }
        return response()->json(new JsonResponse(['status' => 'success']));
    }
}
