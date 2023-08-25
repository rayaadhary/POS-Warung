<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@email.com',
        //     'password' => bcrypt('password'),
        //     'remember_token' => Str::random(10),
        // ]);
        collect(
            [
                [
                    'name' => 'admin',
                    'email' => 'admin@email.com',
                    'password' => bcrypt('password'),
                    'roles' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'name' => 'pemilik',
                    'email' => 'pemilik@email.com',
                    'password' => bcrypt('password'),
                    'roles' => 'pemilik',
                    'remember_token' => Str::random(10),
                ]
            ],
        )->each(function ($user) {
            DB::table('users')->insert($user);
        });
    }
}
