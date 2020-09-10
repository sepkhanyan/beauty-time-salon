<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateSalonRequest;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Salon;
use App\Laravue\Models\SalonCategory;
use Illuminate\Http\Request;

class SalonsController extends BaseController
{

    /**
     * Display the specified salon.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified salon.
     *
     * @param  \app\Http\Requests\UpdateSalonRequest  $request
     * @param  $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSalonRequest $request, Salon $salon)
    {
        $data = $request->except('is_update');
        $salon->update($data);
        return response()->json(new JsonResponse(['salon' => $salon]));
    }

    /**
     * Upload salon logo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoUpload(Request $request, Salon $salon)
    {
        $salon->clearMediaCollection('salon-logo');
        $salon->addMediaFromRequest('logo')->toMediaCollection('salon-logo');
        return response()->json(new JsonResponse(['logo' => $salon->logo]));
    }

    /**
     * Upload salon images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $salon
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesUpload(Request $request, Salon $salon)
    {
        foreach($request['images'] as $image){
            $salon->addMediaFromBase64($image)->toMediaCollection('salon-images');
        }
        return response()->json(new JsonResponse(['images' => $salon->images]));
    }
}
