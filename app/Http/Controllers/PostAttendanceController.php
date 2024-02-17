<?php

namespace App\Http\Controllers;

use App\Models\PostAttendance;
use Illuminate\Http\Request;
use App\Models\PostCourt;
use App\Models\RegistNewCourt;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PostAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $elected_courts = PostCourt::orderBy('elected_date', 'asc')->orderBy('start_time', 'asc')->get();
        $registed_courts = RegistNewCourt::all();

        return view('attendance.post-attendance')->with([
            'elected_courts' => $elected_courts,
            'registed_courts' => $registed_courts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // フォームから送信されたデータを取得
            $attendFlgs = $request->input('attend_flg');
            $attendCourts = $request->input('attendances');
            $userId = $request->input('user_id');

            for ($count = 0; $count < count($attendFlgs); $count++) {
                // バリデーションを追加
                $validator = Validator::make([
                    'user_id' => $userId[$count],
                    'elected_court_id' => $attendCourts[$count],
                    'attend_flg' => $attendFlgs[$count],
                ], [
                    'user_id' => 'required',
                    'elected_court_id' => 'required',
                    'attend_flg' => 'required',
                ]);

                // バリデーションが失敗した場合はエラーメッセージを取得し、リダイレクト
                if ($validator->fails()) {
                    return back()->with('error', '入力が正しくありません。')->withErrors($validator);
                }

                $validated['user_id'] = $userId[$count];
                $validated['elected_court_id'] = $attendCourts[$count];
                $validated['attend_flg'] = $attendFlgs[$count];

                $postAttendance = PostAttendance::create($validated);
            }

            $target_user = User::find($userId[0]);
            if ($target_user->status != 'attending') {
                $target_user->status = 'attending';
                $target_user->save();
            }

            return redirect()->route('post-court.index')->with('message', '保存しました。');
        } catch (\Exception $errors) {
            return back()->with('error', 'エラーが発生しました: ' . $errors->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $postAttendance = PostAttendance::where('user_id', $user_id)->get();
        return view('attendance.show-attendance')->with('postAttendance', $postAttendance);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $user_id)
    {
        // セレクトボックスで選択した値
        $select = intval($request->year_month);

        // デフォルトは2024/3
        if ($select < 1 || $select > 12) {
            $select = 3;
        }

        // 選択された月の始まりと終わりの日付を計算
        $year = 2024; // 仮の年
        $month_start = sprintf('%04d-%02d-01', $year, $select);
        $month_end = date('Y-m-d', strtotime("$month_start +1 month"));

        // 選択された月の範囲でクエリ
        $elected_courts = PostCourt::where('elected_date', '>=', $month_start)
                            ->where('elected_date', '<', $month_end)
                            ->orderBy('elected_date', 'asc')
                            ->orderBy('start_time', 'asc')
                            ->get();

        $postAttendance = PostAttendance::where('user_id', $user_id)->get();
        // $elected_courts = PostCourt::orderBy('elected_date', 'asc')->orderBy('start_time', 'asc')->get();
        $registed_courts = RegistNewCourt::all();
        return view('attendance.edit-attendance')->with([
            'select' => $select,
            'postAttendance' => $postAttendance,
            'elected_courts' => $elected_courts,
            'registed_courts' => $registed_courts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // フォームから送信されたデータを取得
            $attendFlgs = $request->input('attend_flg');
            $attendCourts = $request->input('attendances');
            $userId = $request->input('user_id');
            $attendanceId = $request->input('attendance_id');
            
            for ($count = 0; $count < count($attendFlgs); $count++) {
                // バリデーションを追加
                $validator = Validator::make([
                    'user_id' => $userId[$count],
                    'elected_court_id' => $attendCourts[$count],
                    'attend_flg' => $attendFlgs[$count],
                    'attendanceId' => $attendanceId[$count],
                ], [
                    'user_id' => 'required',
                    'elected_court_id' => 'required',
                    'attend_flg' => 'required',
                    'attendanceId' => 'required',
                ]);

                // バリデーションが失敗した場合はエラーメッセージを取得し、リダイレクト
                if ($validator->fails()) {
                    return back()->with('error', '入力が正しくありません。')->withErrors($validator);
                }

                $validated['user_id'] = $userId[$count];
                $validated['elected_court_id'] = $attendCourts[$count];
                $validated['attend_flg'] = $attendFlgs[$count];

                $postAttendance = PostAttendance::findOrFail($attendanceId[$count]);

                $postAttendance->update($validated);
            }

            return back()->with('message', '更新しました');
        } catch (\Exception $errors) {
            return back()->with('error', 'エラーが発生しました: ' . $errors->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete_user_attendance = PostAttendance::where('user_id', $id)->get();
        $target_user = User::find($id);
        $target_user->status = 'exclusion';
        $target_user->save();
        foreach ($delete_user_attendance as $d_u_attendance) {
            $d_u_attendance->delete();
        }
        return redirect()->route('post-court.index')->with('message', '削除しました');
    }
}
