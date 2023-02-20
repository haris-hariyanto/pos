<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetaData;

class SearchSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetaData::updateOrCreate(
            ['key' => 'searchsettings__enabled'],
            ['value' => 'Y']
        );
    }
}
