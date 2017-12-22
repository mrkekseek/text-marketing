<?php

namespace App\Libraries;

use Carbon\Carbon;

class ApiValidate
{
    const SEND_FROM = 9;
    const SEND_TO = 21;
	const MAX_LENGTH = 500;
    const SUPPORTED_CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@!“#$%&‘()*+,-.?/:;<=> ';

    static public function companyExists($company)
    {
        return auth()->user()->company_name == $request->company;
    }

    static public function companyVerified($company)
    {
        return self::companyExists($company) && auth()->user()->company_status == 'verified';
    }

    static public function messageSymbols($text)
    {
        $text = str_replace(['[$FirstName]', '[$LastName]', '[$Link]'], '', $text);
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

        if (strpos($phone, '1') || strpos($phone, '0')) {
            return false;
        }

        return is_numeric($phone);
    }

    static public function underBlocking($block = true)
    {
        if ( ! empty($block)) {
            $hour = Carbon::now()->hour;
            if ($hour <= self::SEND_FROM || $hour > self::SEND_TO) {
                return false;
            }
        }

        return true;
    }
}