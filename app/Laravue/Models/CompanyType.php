<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyType
 * @package App
 */
class CompanyType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'company_types';

    /**
     * Get all of the type's salons.
     */
    public function salons()
    {
        return $this->hasMany('App\Laravue\Models\Salon','company_type_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Laravue\Models\Category', 'category_has_types',  'category_id','type_id');
    }

}
