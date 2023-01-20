<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function countries()
    {
        return $this->hasMany(Country::class, 'continent', 'name');
    }

    public function states()
    {
        return $this->hasMany(State::class, 'continent', 'name');   
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'continent', 'name');
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'continent', 'name');
    }

    public function hotels()
    {
        return $this->hasMany(\App\Models\Hotel\Hotel::class, 'continent', 'name');
    }
}
