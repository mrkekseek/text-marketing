<?php

use Illuminate\Database\Seeder;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->truncate();
    	
        DB::table('levels')->insert([
            'name' => 'Levels 1',
            'texts' => 1
        ]);

        DB::table('levels')->insert([
            'name' => 'Levels 2',
            'texts' => 2
        ]);

        DB::table('levels')->insert([
            'name' => 'Levels 3',
            'texts' => 65000
        ]);

        DB::table('levels')->insert([
            'name' => 'Levels 4',
            'texts' => 1000
        ]);
    }
}
