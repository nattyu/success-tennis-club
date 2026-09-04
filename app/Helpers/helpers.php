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
    // $default_month_num: month_numが指定されていない場合に表示する月数
    function calculateMonthRange($request, $default_month_num = 1) {
        // セレクトボックスで選択した値がnullでないか確認
        if ($request->year_month) {
            $year_month_list = explode('-', $request->year_month);
            $select_year = intval($year_month_list[0]);
            $select_month = intval($year_month_list[1]);
            $select = $request->year_month;
        } else {
            // nullの場合は今日の年と月を取得
            $select_year = intval(date('Y'));
            $select_month = intval(date('n'));
            $select = $select_year . '-' .  $select_month;
        }

    
        // デフォルトは今月の数字
        if ($select_month < 1 || $select_month > 12) {
            $select_month = date('n'); // 現在の月の数字を取得
        }

        // 表示する月数（1〜3ヶ月）
        $month_num = intval($request->month_num ?? $default_month_num);
        if (!in_array($month_num, getMonthNumChoices(), true)) {
            $month_num = $default_month_num;
        }

        // 選択された月の始まりと、表示月数分進めた月の終わりの日付を計算
        $month_start = sprintf('%04d-%02d-01', $select_year, $select_month);
        $month_end = date('Y-m-t', strtotime($month_start . ' +' . ($month_num - 1) . ' month'));

        return [
            'select' => (string) $select,
            'month_start' => $month_start,
            'month_end' => $month_end,
            'month_num' => $month_num,
        ];
    }
}

if (!function_exists('getMonthNumChoices')) {
    // 表示月数として選択できる値
    function getMonthNumChoices() {
        return [1, 2, 3];
    }
}

if (!function_exists('getFirstDayOfMonth')) {
    function getFirstDayOfMonth($date) {
        return date('Y-m-01', strtotime($date));
    }
}

if (!function_exists('getLastDayOfMonth')) {
    function getLastDayOfMonth($date) {
        return date('Y-m-t', strtotime($date));
    }
}

if (!function_exists('generateYearMonthOptions')) {
    function generateYearMonthOptions($select) {
        $currentMonth = date('n'); // 現在の月を取得
        $currentYear = date('Y');  // 現在の年を取得

        $select_list = explode('-', $select);
        $select_month = intval($select_list[1]);

        $options = '';
        for ($i = -3; $i <= 3; $i++) {
            $month = $currentMonth + $i;
            $year = $currentYear;

            // 月が1未満または12を超えた場合の年と月の調整
            if ($month < 1) {
                $month += 12;
                $year--;
            } elseif ($month > 12) {
                $month -= 12;
                $year++;
            }

            // selected属性の設定
            $selected = $select_month == $month ? 'selected' : '';

            // optionタグの生成
            $options .= '<option value="' . $year . '-' . $month . '" ' . $selected . '>' . $year . '年' . $month . '月</option>';
        }
        return $options;
    }
}

if (!function_exists('generateMonthNumOptions')) {
    function generateMonthNumOptions($month_num) {
        $options = '';
        foreach (getMonthNumChoices() as $num) {
            $selected = intval($month_num) === $num ? 'selected' : '';
            $options .= '<option value="' . $num . '" ' . $selected . '>' . $num . 'ヶ月分</option>';
        }
        return $options;
    }
}

if (!function_exists('getCourtCacheRanges')) {
    // 指定日を含む1〜3ヶ月分の表示範囲（キャッシュキーの元になる開始日・終了日）を全て返す
    function getCourtCacheRanges($date) {
        $ranges = [];
        $firstDayOfMonth = getFirstDayOfMonth($date);

        foreach (getMonthNumChoices() as $month_num) {
            // 表示開始月が指定日の月から遡って何ヶ月目でも、範囲に含まれるものを列挙
            for ($offset = 0; $offset < $month_num; $offset++) {
                $start = date('Y-m-01', strtotime($firstDayOfMonth . ' -' . $offset . ' month'));
                $end = date('Y-m-t', strtotime($start . ' +' . ($month_num - 1) . ' month'));
                $ranges[$start . '_' . $end] = ['start' => $start, 'end' => $end];
            }
        }
        return array_values($ranges);
    }
}

if (!function_exists('clearCourtCaches')) {
    // 指定日を含む全ての表示範囲のキャッシュをクリア
    function clearCourtCaches($date) {
        foreach (getCourtCacheRanges($date) as $range) {
            $suffix = '_' . $range['start'] . '_' . $range['end'];
            \Illuminate\Support\Facades\Cache::forget('postCourts' . $suffix);
            \Illuminate\Support\Facades\Cache::forget('attendances' . $suffix);
            \Illuminate\Support\Facades\Cache::forget('attendanceMatrix' . $suffix);
        }
    }
}

