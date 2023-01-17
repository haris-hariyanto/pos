<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class Place extends Model
{
    use HasFactory, Compoships;

    protected $guarded = [];

    public $timestamps = false;

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class, 'continent', 'name');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class, ['country', 'continent'], ['name', 'continent']);
    }
}
