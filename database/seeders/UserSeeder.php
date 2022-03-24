<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Sanjay Kandel',
            'email'=>'kandelsanjay1111@gmail.com'
        ]);
    }
}
