<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetaData;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetaData::insert([
            ['key' => 'settings__website_name', 'value' => config('app.name')],
            ['key' => 'settings__header_script', 'value' => ''],
            ['key' => 'settings__footer_script', 'value' => ''],
        ]);
    }
}
