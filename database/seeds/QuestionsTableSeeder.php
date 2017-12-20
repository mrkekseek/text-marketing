<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->truncate();

        DB::table('questions')->insert([
            'text' => 'Can you please rate me?',
            'type' => 'star',
        ]);

        DB::table('questions')->insert([
            'text' => 'Why?',
            'type' => 'essay',
        ]);
    }
}
