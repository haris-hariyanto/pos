<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class, 'continent', 'name');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class, 'country', 'name');
    }
}
