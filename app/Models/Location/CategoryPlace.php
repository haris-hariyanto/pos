<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class CategoryPlace extends Model
{
    use HasFactory, Compoships;

    protected $table = 'category_place';

    protected $guarded = [];

    public $timestamps = false;

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
