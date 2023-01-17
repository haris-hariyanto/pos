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
}
