<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chain()
    {
        return $this->belongsTo(Chain::class)->withDefault([
            'name' => '-',
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault([
            'name' => '-',
        ]);
    }

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class);
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\Location\State::class);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\Location\City::class);
    }
}
