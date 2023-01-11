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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->integer('state_id')->nullable();
            $table->string('state')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('country')->nullable();
            $table->integer('continent_id')->nullable();
            $table->string('continent')->nullable();
            $table->enum('is_scraped', ['Y', 'N', 'PROCESS'])->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
