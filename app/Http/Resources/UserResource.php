<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            ),
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            'password_created' => $this->password_created,
            'salon' => [
                'id' => $this->salon->id,
                'name' => $this->salon->name,
                'description' => $this->salon->description,
                'phone' => $this->salon->phone_number,
                'status' => $this->salon->status,
                'social' => $this->salon->social,
                'notifications_lang' => 'English',
                'logo' => $this->salon->logo,
                'images' => $this->salon->images,
                'company_type_id' => $this->salon->company_type_id,
            ],
            'avatar' => 'https://i.pravatar.cc',
        ];
    }
}
