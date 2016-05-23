<?php

use Illuminate\Database\Seeder;

class DefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
         'name' => "Admin",
         'email' => "mate.nakic3@gmail.com",
         'password' => bcrypt('123456'),
         'role' => 2
        ]);
    }
}
