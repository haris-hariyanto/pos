<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hotel\Hotel;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('price')->nullable();
        });

        $segment = 1;
        while (true) {
            $take = 1000;
            $skip = ($segment - 1) * $take;

            $hotels = Hotel::skip($skip)->take($take)->get();
            
            if (count($hotels) >= 1) {
                foreach ($hotels as $hotel) {
                    $price = false;
                    if (!empty($hotel->rates_from)) {
                        $price = $hotel->rates_from;
                    }
                    elseif (!empty($hotel->rates_from_exclusive)) {
                        $price = $hotel->rates_from_exclusive;
                    }

                    if ($price != false) {
                        $hotel->update(['price' => $price]);
                    }
                } // [END] foreach
            }
            else {
                break;
            }

            $segment++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
