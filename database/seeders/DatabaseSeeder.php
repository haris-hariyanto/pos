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
                'email' => 'harishariyanto@naver.com',
                'email_verified_at' => now(),
                'group_id' => 1,
            ]);

        \App\Models\User::factory()
            ->count(100)
            ->state(new Sequence(
                ['email_verified_at' => now()],
                ['email_verified_at' => null],
            ))
            ->create();
        
        $this->call([
            GroupSeeder::class,
            PageSeeder::class,
        ]);
    }
}
