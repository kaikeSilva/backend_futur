<?php
if (!function_exists('to_string_time')) {
    function to_string_time($time)
    {
        $horas = intval($time/60);
        $minutos = intval($time%60);

        return $horas." h ".$minutos." min";
    }
}