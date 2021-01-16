<?php

use App\User;
use Carbon\Carbon;

if (!function_exists('to_string_time')) {
    function to_string_time($time)
    {
        $hour = floor($time/60);
        $minutes = $time%60;

        if ($hour == 0) return $minutes." min"; 
        return $hour."h ".$minutes." min";
    }
}

if (!function_exists('to_string_date')) {
    /** @param Carbon $date  */
    function to_string_date(Carbon $date)
    {
        $dayOfTheWeek = $date->dayOfWeek;
        $day = $date->format('d');
        $month = User::MONTHS[$date->month];
        $year = $date->format('Y');
        $weekday = User::WEEK_DAYS[$dayOfTheWeek];

        return $day." ".$month." de ".$year;
    }
}

if (!function_exists('week_day')) {
    /** @param Carbon $date  */
    function week_day(Carbon $date)
    {
        return User::WEEK_DAYS[$date->dayOfWeek];
    }
}