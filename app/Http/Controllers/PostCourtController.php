<?php

namespace App\Http\Controllers;

use App\Models\PostCourt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RegistNewCourt;
use App\Models\PostAttendance;

class PostCourtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $postCourts = PostCourt::where('elected_date', '>=', $month_start)
                            ->where('elected_date', '<', $month_end)
                            ->orderBy('elected_date', 'asc')
                            ->orderBy('start_time', 'asc')
                            ->get();
        
        $users = User::all();
        $attendances = PostAttendance::all();
        return view('court.index-court', compact('postCourts', 'select'))
            ->with([
                'users' => $users,
                'postCourts' => $postCourts,
                'attendances' => $attendances
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $registed_courts = RegistNewCourt::all();
        $start_times = ['7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
        $end_times = ['9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

        return view('court.post-court')->with([
            'registed_courts' => $registed_courts,
            'start_times' => $start_times,
            'end_times' => $end_times,
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

        return back()->with('message', '保存しました');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $postCourt = PostCourt::find($id);
        return view('court.show-court', compact('postCourt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCourt $postCourt)
    {
        $registed_courts = RegistNewCourt::all();
        $start_times = ['7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
        $end_times = ['9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

        return view('court.edit-court', compact('postCourt'))->with([
            'registed_courts' => $registed_courts,
            'start_times' => $start_times,
            'end_times' => $end_times,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostCourt $postCourt)
    {
        $validated = $request->validate([
            'court_id' => 'required',
            'court_number' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'elected_date' => 'required',
        ]);

        $validated['user_id'] = auth()->id();

        $postCourt->update($validated);

        return back()->with('message', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete_court = PostCourt::find($id);
        $delete_attendance = PostAttendance::where('elected_court_id', $id)->get();
        $delete_court->delete();
        foreach ($delete_attendance as $d_attendance) {
            $d_attendance->delete();
        }
        return redirect()->route('post-court.index')->with('message', '削除しました');
    }
}
