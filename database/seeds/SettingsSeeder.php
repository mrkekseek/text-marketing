<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('settings')->truncate();
    	
        DB::table('settings')->insert([
            'text' => 'Hi [$FirstName] - thanks for signing up! We are now sending your info to HomeAdvisor to get you connected. For any questions, please text back - thanks!',
            'text_code' => 'thankyou',
        ]);

        DB::table('settings')->insert([
            'text' => 'Hi [$FirstName] - we are still waiting on HomeAdvisor to connect you, just waiting on them. Thanks!',
            'text_code' => 'twodays',
        ]);
        
        DB::table('settings')->insert([
            'text' => 'Hi [$FirstName] - sorry for the continued delay - we are pushing HomeAdvisor hard to connect you, just waiting on them. Thanks!',
            'text_code' => 'fourdays',
        ]);
    }
}
