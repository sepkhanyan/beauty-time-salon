<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class SalonCategory extends Model
{
    protected $table = 'salon_categories';

    protected $fillable = [
        'salon_id', 'category_id', 'services', 'sort_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'services' => 'array',
    ];


    public function scopeTitle($query, $val)
    {
        if (!empty($val)) {
            return $query->whereHas('category', function ($q) use ($val) {
                $q->where('title', 'LIKE', '%' . $val . '%');
            });
        }
    }

    public function salon()
    {
        return $this->belongsTo('App\Laravue\Models\Salon');
    }

    public function category()
    {
        return $this->belongsTo('App\Laravue\Models\Category');
    }
}
