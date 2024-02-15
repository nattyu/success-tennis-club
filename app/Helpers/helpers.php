<?php
  
use Carbon\Carbon;
  
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertHisToHi')) {
    function convertHisToHi($time)
    {
        return Carbon::createFromFormat('H:i:s', $time)->format('G:i');
    }
}

if (! function_exists('convertyyyymmddTomd')) {
    function convertyyyymmddTomd($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('n/j');
    }
}

if (!function_exists('getDayOfWeek')) {
    function getDayOfWeek($date)
    {
        // Carbonライブラリを使用して日本語の曜日を取得
        return Carbon::createFromFormat('Y-m-d', $date)->isoFormat('ddd');
    }
}