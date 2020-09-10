<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Employees extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'employees';

    protected $fillable = [
        'name', 'phone_number', 'status', 'position_id'
    ];

    protected $appends = [
        'image',
        'images'
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);

        $this->addMediaCollection('employees-image');
        $this->addMediaCollection('employees-images');
    }

    public function getImageAttribute()
    {
        $response = $this->getMedia('employees-image')->first();
        if ($response) {
            return $response->getUrl('thumb');
        } else {
            return '';
        }
    }

    public function getImagesAttribute(){
        $response = $this->getMedia('employees-images')->mapToGroups(function ($item) {
            return [$item->getUrl('thumb')];
        });
        return count($response) > 0 ? $response[0] : [];
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

}
