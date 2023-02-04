<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class Hotel extends Model
{
    use HasFactory, Compoships;

    protected $guarded = [];

    public function reviews()
    {
        return $this->hasMany(\App\Models\Hotel\Review::class);
    }

    public function chain()
    {
        return $this->belongsTo(Chain::class, 'chain', 'name')->withDefault([
            'name' => '-',
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand', 'name')->withDefault([
            'name' => '-',
        ]);
    }

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class, 'continent', 'name');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class, ['country', 'continent'], ['name', 'continent']);
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\Location\State::class, ['state', 'country'], ['name', 'country']);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\Location\City::class, ['city', 'country'], ['name', 'country']);
    }
}
