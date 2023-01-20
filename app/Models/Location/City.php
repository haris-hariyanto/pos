<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class City extends Model
{
    use HasFactory, Compoships;

    protected $guarded = [];

    public $timestamps = false;

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class, 'continent', 'name');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\Location\City::class, ['city', 'country'], ['name', 'country']);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class, 'country', 'name');
    }

    public function hotels()
    {
        return $this->hasMany(\App\Models\Hotel\Hotel::class, ['city', 'country'], ['name', 'country']);
    }
}
