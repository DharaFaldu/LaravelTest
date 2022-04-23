<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 111; $i++) {
            $email = $faker->unique()->safeEmail;
            $password = explode('@',$email);
            if(strlen($password[0]) < 8) {
                $passwordNew = $password[0].str_repeat("1", 8-strlen($password[0]));
            }
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $email,
                'role_id' => rand(1,3),
                'password' => Hash::make($passwordNew),
                'email_verified_at' => now(),
            ]);
        }
    }
}
