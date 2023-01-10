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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->text('slug');
            $table->text('name');
            $table->enum('type', ['PLACE', 'CITY', 'STATE', 'COUNTRY', 'CONTINENT']);
            $table->string('longitude');
            $table->string('latitude');
            $table->integer('city_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('continent_id')->nullable();
            $table->enum('is_hotels_scraped', ['Y', 'N', 'PROCESS'])->default('N');
            $table->integer('hotels_nearby')->default(0);
            $table->text('additional_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
};
