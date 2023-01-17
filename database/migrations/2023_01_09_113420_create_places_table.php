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
            $table->text('address')->nullable();
            $table->string('longitude');
            $table->string('latitude');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('continent')->nullable();
            $table->string('gmaps_id')->nullable();
            $table->string('category')->nullable();
            $table->integer('category_id')->nullable();
            $table->enum('is_hotels_scraped', ['Y', 'N', 'PROCESS'])->default('N');
            $table->integer('hotels_nearby')->default(0);
            $table->integer('user_ratings_total')->default(0);
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
