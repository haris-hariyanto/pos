<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetaData;

class ReviewsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetaData::updateOrCreate(
            ['key' => 'reviewssettings__allow_new_reviews'],
            ['value' => 'Y']
        );

        MetaData::updateOrCreate(
            ['key' => 'reviewssettings__allow_reply_to_reviews'],
            ['value' => 'Y']
        );

        MetaData::updateOrCreate(
            ['key' => 'reviewssettings__reviews_must_be_approved'],
            ['value' => 'Y']
        );

        MetaData::updateOrCreate(
            ['key' => 'reviewssettings__replies_must_be_approved'],
            ['value' => 'Y']
        );
    }
}
