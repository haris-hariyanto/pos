<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPlace extends Model
{
    use HasFactory;

    protected $table = 'category_place';

    protected $guarded = [];

    public $timestamps = false;
}
