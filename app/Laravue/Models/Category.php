<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'categories';

    public function services()
    {
        return $this->hasMany('App\Laravue\Models\CategoryService');
    }

    public function types()
    {
        return $this->belongsToMany('App\Laravue\Models\CompanyType', 'category_has_types', 'category_id','type_id');
    }

    public function salonCategories()
    {
        return $this->hasMany('App\Laravue\Models\SalonCategory', 'category_id');
    }
}
