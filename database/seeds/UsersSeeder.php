<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->truncate();
    	
        DB::table('users')->insert([
            'type' => 1,
            'owner' => 1,
            'firstname' => 'John',
            'lastname' => 'Smith',
            'email' => 'uri@medicalreputation.com',
            'password' => bcrypt('1234'),
            'phone' => '9179726832',
            'view_phone' => '9179726832',
            'active' => 1,
        ]);

        DB::table('users')->insert([
            'type' => 1,
            'firstname' => 'John',
            'lastname' => 'Smith',
            'email' => 'id@div-art.com',
            'password' => bcrypt('1234'),
            'phone' => '981745686',
            'view_phone' => '981745686',
            'active' => 1,
        ]);
    }
}
