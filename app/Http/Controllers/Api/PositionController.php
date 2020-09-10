<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionResource;
use App\Laravue\JsonResponse;
use Illuminate\Http\Request;
use App\Position;
use Illuminate\Support\Arr;
use Validator;

class PositionController extends Controller
{

    const ITEM_PER_PAGE = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $searchParams = $request->all();
        $positions = Position::query()->where('status','!=','deleted')->with('employees');
        $positions = $positions->title($request->title);
        $positions = $positions->get();
//        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        return response()->json(new JsonResponse(['positions' => $positions, 'total' => count($positions)]));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'title' => ['required'],
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $position = Position::create($request->all());

            return new PositionResource($position);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $position = Position::find($id);
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'title' => ['required'],
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $position->update([
                'title' => $params['title'],
                'description' => $params['description'],
            ]);
            $position->save();

            return new PositionResource($position);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $position =   Position::find($id);
        $position->status="deleted";
        $position->save();

        return response()->json(new JsonResponse(['position' => $position]));

    }


    public function toggleStatus($id)
    {
        $position = Position::find($id);
        if($position->status == "active")
        {
            $position->status = "disabled";
        }else {
            $position->status = "active";
        }
        $position->save();
        return response()->json(new JsonResponse(['position' => $position]));

    }


    private function getValidationRules($isNew = true)
    {
        return [
            'title' => 'required',
        ];
    }
}
