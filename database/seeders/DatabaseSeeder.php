<?php

namespace Database\Seeders;

use App\Models\Audio;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Omar EL ATMANI',
            'email'    => 'omar@omar',
            'password' => Hash::make('omar@omar'),
            'is_admin' => 1,
        ]);
        User::factory(2)->create();
        Audio::factory(10)->create();
        Topic::factory(200)->create();
    }
}
