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
            'title' => '[$user_firstname] [$user_lastname] - Your Contractor',
            'text' => '[$client_firstname], can you please take 3 seconds & answer 1 question? It will really help me, thanks! Click - [$Link]',
            'email_text' => 'It will really help me, thanks!',
            'email_subject' => '[$client_firstname], can you please take 3 seconds?',
            'email_sender' => '[$user_firstname] - Your Contractor',
            'type' => 0,
        ]);
    }
}
