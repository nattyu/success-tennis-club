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

if (!function_exists('convertCourtName')) {
    function convertCourtName($courtname)
    {
        switch ($courtname) {
            case '流山市コミュニティプラザ':
                return 'コミプラ';
            case '流山市総合運動公園':
                return 'キッコーマン';
            case '柏の葉庭球場':
                return '柏市庭球場';
            case '柏の葉公園庭球場':
                return '柏の葉公園';
            default:
                if (strpos($courtname, 'testcourt') === 0) {
                    return 'test';
                }
                return $courtname; // マッチしない場合は元の名前を返す
        }
    }
}

if (!function_exists('convertCourtNumber')) {
    function convertCourtNumber($courtNumber)
    {
        // 全角を半角に変換
        $courtNumber = mb_convert_kana($courtNumber, 'n', 'UTF-8');
        
        // 数字の全角半角判定
        if (is_numeric($courtNumber)) {
            // 「番コート」を付与
            $courtNumber .= '番コート';
        } elseif ($courtNumber === '屋内') {
            // 「屋内」の場合はそのまま返す
            return $courtNumber;
        }
        return $courtNumber;
    }
}

if (!function_exists('calculateMonthRange')) {
    function calculateMonthRange($request) {
        // セレクトボックスで選択した値
        $select = intval($request->year_month);
    
        // デフォルトは今月の数字
        if ($select < 1 || $select > 12) {
            $select = date('n'); // 現在の月の数字を取得
            if ($select < 3) {
                $select = 3;
            }
        }
    
        // 選択された月の始まりと終わりの日付を計算
        $year = date('Y'); // 西暦の数字
        $month_start = sprintf('%04d-%02d-01', $year, $select);
        $month_end = date('Y-m-t', strtotime($month_start));
    
        return ['select' => (string) $select, 'month_start' => $month_start, 'month_end' => $month_end];
    }
}