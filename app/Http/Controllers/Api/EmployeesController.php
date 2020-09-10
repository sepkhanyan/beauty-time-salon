<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeesResource;
use App\Laravue\Models\Employees;
use App\Position;
use Illuminate\Http\Request;
use App\Laravue\JsonResponse;
use Validator;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $searchParams = $request->all();
        $employees = Employees::with('position');
        $employees = $employees->get();
        $positions = Position::all()->where('status', '!=', 'deleted');
        return response()->json(new JsonResponse(['positions' => $positions, 'employees' => $employees, 'total' => count($employees)]));


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
                    'name' => ['required'],
                    'phone_number' => ['required'],
                    'position_id' => ['required'],
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $employees = Employees::create($request->all());

            return new EmployeesResource($employees);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employees $employee)
    {
        $employee->load('position');
        return new EmployeesResource($employee);
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

        $employees = Employees::find($id);
        $params = $request->except('image');
        $employees->update($params);

        return new EmployeesResource($employees);

    }

    public function toggleStatus($id)
    {
        $employees = Employees::find($id);
        if ($employees->status == "active") {
            $employees->status = "disabled";
        } else {
            $employees->status = "active";
        }
        $employees->save();
        return response()->json(new JsonResponse(['employees' => $employees]));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function imageUpload(Request $request, Employees $employees)
    {
            $employees->clearMediaCollection('employees-image');
            $employees->addMediaFromBase64($request->image)->toMediaCollection('employees-image');
            return response()->json(new JsonResponse(['image' => $employees->image]));
    }


    public function imagesUpload(Request $request, Employees $employees)
    {
        foreach($request['images'] as $image){
            $employees->addMediaFromBase64($image)->toMediaCollection('employees-images');
        }
        return response()->json(new JsonResponse(['images' => $employees->images]));
    }

    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'phone_number' => 'required',
            'position_id' => 'required',
        ];
    }
}
