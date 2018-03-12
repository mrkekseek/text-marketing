<?php

namespace App\Libraries;

use Carbon\Carbon;
use App\Seance;
use App\Text;
use App\Dialog;
use App\Appointment;

class ApiValidate
{
    const SEND_FROM = 9;
    const SEND_TO = 21;
	const MAX_LENGTH = 500;
    const SUPPORTED_CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@!“#$%&‘'’()*+,-.?/:;<=> ";

    static public function companyExists($company, $user)
    {
        return $user->company_name == $company;
    }

    static public function companyVerified($company, $user)
    {
        return self::companyExists($company, $user) && $user->company_status == 'verified';
    }

    static public function messageSymbols($text)
    {
        $text = str_replace(['[$FirstName]', '[$LastName]', '[$HAPage]', '[$Link]', '[$JobPics]', "\n"], '', $text);
        for ($i = 0, $count = strlen($text); $i < $count; $i++) {
            if (strpos(self::SUPPORTED_CHARACTERS, $text[$i]) === false) {
               return false;
            }
        }

        return true;
    }

    static public function messageLength($text, $company, $length = null)
    {
        $length = empty($length) ? self::MAX_LENGTH : $length;
        $optout = ' Txt STOP to OptOut';
        $realLength = strlen($text) + 2 + strlen($company) + strlen($optout);

        return $realLength <= $length;
    }

    static public function phoneFormat($phone)
    {
        if (strlen($phone) != 10) {
            return false;
        }

        if (strpos($phone, '1') === 0 || strpos($phone, '0') === 0) {
            return false;
        }

        return is_numeric($phone);
    }

    static public function underBlocking($date, $block = true)
    {
        if ( ! empty($block)) {
            $hour = $date->hour;
            if ($hour < self::SEND_FROM || $hour >= self::SEND_TO) {
                return true;
            }
        }

        return false;
    }

    static public function underLimit($phone, $date = '')
    {
        $date = ! empty($date) ? $date : Carbon::now();
        $seances = Seance::where('date', '>=', $date->subHours(24))->withCount(['clients' => function($query) use($phone) {
            return $query->where('phone', $phone);
        }])->get();

        foreach ($seances as $seance) {
            if ($seance->clients_count > 0) {
                return true;
            }
        }
        return false;
    }

    static public function underLimitMarketing($client_id, $date = '')
    {
        $date = ! empty($date) ? $date : Carbon::now();
        $texts = Text::where('send_at', '>=', $date->subHours(24))->withCount(['receivers' => function($query) use($client_id) {
            return $query->where('client_id', $client_id);
        }])->get();

        foreach ($texts as $text) {
            if ($text->receivers_count > 0) {
                return true;
            }
        }
        return false;
    }

    static public function underLimitDialog($client_id)
    {
        $dialog = Dialog::where('created_at', '>=', Carbon::now()->subHours(24))->where('clients_id', $client_id)->get();
        if ($dialog->isEmpty()) {
            return false;
        }
        return true;
    }

    static public function underLimitAppointment($client_id)
    {
        $appointment = Appointment::where('created_at', '>=', Carbon::now()->subHours(24))->where('client_id', $client_id)->get();
        if ($appointment->isEmpty()) {
            return false;
        }
        return true;
    }
}