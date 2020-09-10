<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['title', 'description', 'status'];


    public function scopeTitle($query, $val)
    {
        if (!empty($val)) {
            return $query->where('title', 'LIKE', '%' . $val . '%');
        }
    }

    public function employees()
    {
        return $this->hasMany('App\Laravue\Models\Employees');
    }

}
