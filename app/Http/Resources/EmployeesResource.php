<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'position' => [
                'id' => $this->position->id,
                'title' => $this->position->title,
                'description' => $this->position->description,
            ],
            'image' => $this->image,
            'images' => $this->images,
        ];
    }
}
