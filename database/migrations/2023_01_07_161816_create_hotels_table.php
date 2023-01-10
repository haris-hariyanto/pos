<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->integer('chain_id')->nullable();
            $table->string('chain')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('name');
            $table->string('formerly_name')->nullable();
            $table->string('translated_name')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('country_iso_code')->nullable();
            $table->string('continent')->nullable();
            $table->string('star_rating')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->text('url')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('number_of_rooms')->nullable();
            $table->string('number_of_floors')->nullable();
            $table->string('year_opened')->nullable();
            $table->string('year_renovated')->nullable();
            $table->text('overview')->nullable();
            $table->string('rates_from')->nullable();
            $table->integer('continent_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('number_of_reviews')->default(0);
            $table->string('rating_average')->nullable();
            $table->string('rates_currency')->nullable();
            $table->string('rates_from_exclusive')->nullable();
            $table->string('accommodation_type')->nullable();
            $table->text('photos')->nullable();
            $table->text('additional_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
