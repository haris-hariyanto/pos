<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hotel\Review;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('source')->nullable();
            $table->integer('parent_id')->default(0);
        });

        Review::where('id', '>', 0)->update([
            'source' => 'google',
            'is_accepted' => 'Y',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->dropColumn('parent_id');
        });
    }
};
