<?php

namespace App\Models\Hotel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPlace extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'hotel_place';

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function place()
    {
        return $this->belongsTo(\App\Models\Location\Place::class);
    }
}
