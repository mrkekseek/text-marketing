<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homeadvisor extends Model
{
    const FIRST_FOLLOWUP_ACTIVE = 1;
    const SECOND_FOLLOWUP_ACTIVE = 0;
    const CLICK_ALERT_ACTIVE = 0;
    const FIRST_DELAY_AFTER_SIGNUP = 2;
    const SECOND_DELAY_AFTER_SIGNUP = 4;

    protected $guarded = [];

    protected $attributes = [
        'emails' => '',
        'first_followup_text' => '',
        'second_followup_text' => '',
        'first_followup_delay' => 0,
        'second_followup_delay' => 0,
        'first_followup_active' => 0,
        'second_followup_active' => 0,
    ];
}
