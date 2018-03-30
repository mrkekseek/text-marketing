<?php

use Illuminate\Database\Seeder;

class DefaultTextsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('default_texts')->truncate();
    	
        DB::table('default_texts')->insert([
            'thank_you_signup' => 'Hi [$FirstName] - thanks for signing up! We sent your info to HomeAdvisor to get connected. For any Qs, please text back - Thanks!',
            'two_days_not_active' => 'Hi [$FirstName] - we are still waiting on HomeAdvisor to connect you, just waiting on them. Thanks!',
            'four_days_not_active' => 'Hi [$FirstName] - sorry for the continued delay - we are pushing HomeAdvisor hard to connect you, just waiting on them. Thanks!',
            'new_user' => 'Hi - would you like to use our service?',
            'instant' => '[$FirstName], we\'re happy to offer a free estimate! Please click [$Website] or [$OfficePhone]. Thanks!',
            'first_followup' => 'Free estimate! Please text back the best time for us to call you. Thanks!',
            'second_followup' => 'Last text - want a free estimate? Click [$Website] or [$OfficePhone]. Thanks!',
        ]);
    }
}
