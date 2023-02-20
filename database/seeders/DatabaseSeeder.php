<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()
            ->create([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
            ]);
        
        $this->call([
            PageSeeder::class,
            SettingSeeder::class,
            ReviewsSettingsSeeder::class,
            SearchSettingsSeeder::class,
        ]);
    }
}
