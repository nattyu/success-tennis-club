<?php

namespace App\Http\Controllers;

use App\Models\PostCourt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RegistNewCourt;
use App\Models\PostAttendance;
use Illuminate\Support\Facades\Cache;

class PostCourtController extends Controller
{
    // 定数
    private $start_times = ['6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
    private $end_times = ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

    // キャッシュキーを生成
    private function getCacheKey($prefix, $date) {
        $firstDayOfMonth = getFirstDayOfMonth($date); // 月の初日を取得;
        $lastDayOfMonth = getLastDayOfMonth($date); // 月の最終日を取得;
        return $prefix . '_' . $firstDayOfMonth . '_' . $lastDayOfMonth;
    }

    // キャッシュのクリア
    private function clearRelatedCaches($date) {
        $postCourtsKey = $this->getCacheKey('postCourts', $date);
        $attendancesKey = $this->getCacheKey('attendances', $date);
        $attendanceMatrixKey = $this->getCacheKey('attendanceMatrix', $date);

        Cache::forget($postCourtsKey);
        Cache::forget($attendancesKey);
        Cache::forget($attendanceMatrixKey);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // リクエストから選択された年月の範囲を計算
        $select_and_month_num = calculateMonthRange($request);
        $select = $select_and_month_num['select'];

        // キャッシュのキーを月の範囲に基づいて設定
        $cacheKeyPostCourts = 'postCourts_' . $select_and_month_num['month_start'] . '_' . $select_and_month_num['month_end'];
        $cacheKeyAttendances = 'attendances_' . $select_and_month_num['month_start'] . '_' . $select_and_month_num['month_end'];
        $cacheKeyAttendanceMatrix = 'attendanceMatrix_' . $select_and_month_num['month_start'] . '_' . $select_and_month_num['month_end'];

        // postCourtsデータをキャッシュから取得、またはクエリ実行してキャッシュに保存
        $postCourts = Cache::remember($cacheKeyPostCourts, 3600, function () use ($select_and_month_num) {
            return PostCourt::where('elected_date', '>=', $select_and_month_num['month_start'])
                            ->where('elected_date', '<=', $select_and_month_num['month_end'])
                            ->orderBy('elected_date', 'asc')
                            ->orderBy('start_time', 'asc')
                            ->with('user', 'court')
                            ->get();
        });

        // ユーザーデータを取得して、ログインユーザーを先頭にする
        $users = User::select('id', 'nickname')->get();
        $authUserId = auth()->id(); // ログインユーザーのIDを取得
        $users = $users->sortByDesc(function ($user) use ($authUserId) {
            return $user->id === $authUserId;
        });

        // attendancesデータをキャッシュから取得、またはクエリ実行してキャッシュに保存
        $attendances = Cache::remember($cacheKeyAttendances, 3600, function () use ($postCourts) {
            $postCourtIds = $postCourts->pluck('id')->toArray();
            return PostAttendance::whereIn('elected_court_id', $postCourtIds)
                                ->select('user_id', 'elected_court_id', 'attend_flg')
                                ->get();
        });

        // 出席情報のマトリックスをキャッシュから取得、または構築してキャッシュに保存
        $attendanceMatrix = Cache::remember($cacheKeyAttendanceMatrix, 3600, function () use ($users, $postCourts, $attendances) {
            $matrix = [];
            foreach ($users as $user) {
                foreach ($postCourts as $p_court) {
                    $attend_array = $attendances->where('user_id', $user->id)->where('elected_court_id', $p_court->id)->first();
                    $matrix[$user->id][$p_court->id] = $attend_array ? $attend_array->attend_flg : 'null';
                }
            }
            return $matrix;
        });

        // 集計データの準備
        $attendanceCounts = [];
        foreach ($postCourts as $p_court) {
            $attendanceCounts[$p_court->id]['〇'] = $attendances->where('elected_court_id', $p_court->id)->where('attend_flg', '〇')->count();
            $attendanceCounts[$p_court->id]['△'] = $attendances->where('elected_court_id', $p_court->id)->where('attend_flg', '△')->count();
        }

        // ビューにデータを渡してレンダリング
        return view('court.index-court', compact('postCourts', 'select', 'users', 'attendanceMatrix', 'attendanceCounts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $registed_courts = RegistNewCourt::all();
        
        return view('court.post-court')->with([
            'registed_courts' => $registed_courts,
            'start_times' => $this->start_times,
            'end_times' => $this->end_times,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required',
            'court_number' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'elected_date' => 'required',
        ]);

        $validated['user_id'] = auth()->id();

        $postCourt = PostCourt::create($validated);

        // 関連するキャッシュのクリア
        $this->clearRelatedCaches($validated['elected_date']);

        $users = User::all();
        $elected_court_id = $postCourt->id;

        $post_null_attendance = [
            'elected_court_id' => $elected_court_id,
            'attend_flg' => '-'
        ];

        foreach ($users as $user) {
            $post_null_attendance['user_id'] = $user->id;
            PostAttendance::create($post_null_attendance);
        }

        return back()->with('message', '保存しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $postCourt = PostCourt::find($id);
        
        // 前のレコードを取得
        $previousCourt = PostCourt::where(function ($query) use ($postCourt) {
            $query->where('elected_date', '<', $postCourt->elected_date)
                ->orWhere(function ($query) use ($postCourt) {
                    $query->where('elected_date', '=', $postCourt->elected_date)
                            ->where('start_time', '<', $postCourt->start_time);
                });
        })
        ->where('id', '!=', $postCourt->id)  // 現在のレコードを除外
        ->orderBy('elected_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->first();

        // 次のレコードを取得
        $nextCourt = PostCourt::where(function ($query) use ($postCourt) {
            $query->where('elected_date', '>', $postCourt->elected_date)
                ->orWhere(function ($query) use ($postCourt) {
                    $query->where('elected_date', '=', $postCourt->elected_date)
                            ->where('start_time', '>', $postCourt->start_time);
                });
        })
        ->where('id', '!=', $postCourt->id)  // 現在のレコードを除外
        ->orderBy('elected_date', 'asc')
        ->orderBy('start_time', 'asc')
        ->first();

        $users = User::all();
        $attendance_OK_member = PostAttendance::where('elected_court_id', $id)->where('attend_flg', '〇')->get();
        $attendance_Yet_member = PostAttendance::where('elected_court_id', $id)->where('attend_flg', '△')->get();
        return view('court.show-court', compact('postCourt', 
                                                'users',
                                                'previousCourt',
                                                'nextCourt', 
                                                'attendance_OK_member', 
                                                'attendance_Yet_member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCourt $postCourt)
    {
        $registed_courts = RegistNewCourt::all();
        $users = User::all();

        return view('court.edit-court', compact('postCourt'))->with([
            'users' => $users,
            'registed_courts' => $registed_courts,
            'start_times' => $this->start_times,
            'end_times' => $this->end_times,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostCourt $postCourt)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'court_id' => 'required',
            'court_number' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'elected_date' => 'required',
        ]);

        // 関連するキャッシュのクリア
        $this->clearRelatedCaches($validated['elected_date']);

        $postCourt->update($validated);

        return redirect()->route('post-court.index')->with('message', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete_court = PostCourt::find($id);
        $delete_attendance = PostAttendance::where('elected_court_id', $id)->get();
        $elected_date = $delete_court->elected_date; // 日付を取得

        $delete_court->delete();
        foreach ($delete_attendance as $d_attendance) {
            $d_attendance->delete();
        }

        // 関連するキャッシュのクリア
        $this->clearRelatedCaches($elected_date);

        return redirect()->route('post-court.index')->with('message', '削除しました');
    }
}
