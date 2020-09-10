<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Salon extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'salons';

    protected $fillable = [
        'user_id', 'name', 'phone_number', 'status', 'description', 'social', 'company_type_id'
    ];

    protected $appends = [
        'logo',
        'images'
    ];


    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);

        $this->addMediaCollection('salon-logo');
        $this->addMediaCollection('salon-images');
    }

    public function getLogoAttribute(){
        $response = $this->getMedia('salon-logo')->first();
        if($response){
            return $response->getUrl('thumb');
        }else{
            return '';
        }
    }

    public function getImagesAttribute(){
        $response = $this->getMedia('salon-images')->mapToGroups(function ($item) {
            return [$item->getUrl('thumb')];
        });
       return count($response) > 0 ? $response[0] : [];
    }

    public function user()
    {
        return $this->belongsTo('App\Laravue\Models\User');
    }

    /**
     * Get the type of salon.
     */
    public function companyTypes()
    {
        return $this->belongsTo('App\Laravue\Models\CompanyType');
    }

    public function categories()
    {
        return $this->hasMany('App\Laravue\Models\SalonCategory', 'salon_id');
    }

}
