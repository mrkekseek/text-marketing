<?php

use Illuminate\Database\Seeder;

class SurveysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('surveys')->truncate();

        DB::table('surveys')->insert([
            'text' => '[$FirstName], can you please take 3 seconds & answer 1 question? It will really help me, thanks! Click - [$Link]',
            'email' => 'It will really help me, thanks!',
            'subject' => '[$FirstName], can you please take 3 seconds?',
            'sender' => '[$myFirstName] - Your Contractor'
        ]);
    }
}
