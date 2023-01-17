<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function continent()
    {
        return $this->belongsTo(\App\Models\Location\Continent::class, 'continent', 'name');
    }

    public function cities()
    {
        return $this->hasMany(\App\Models\Location\City::class, 'country', 'name');
    }

    public function states()
    {
        return $this->hasMany(\App\Models\Location\State::class, 'country', 'name');
    }

    public function places()
    {
        return $this->hasMany(\App\Models\Location\Place::class, 'country', 'name');
    }
}
