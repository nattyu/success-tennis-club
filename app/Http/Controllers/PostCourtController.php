<?php

namespace App\Http\Controllers;

use App\Models\PostCourt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RegistNewCourt;
use App\Models\PostAttendance;

class PostCourtController extends Controller
{
    // 定数
    private $start_times = ['6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
    private $end_times = ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 選択された年月を取得
        $select_and_month_num = calculateMonthRange($request);
        $select = $select_and_month_num['select'];

        // 選択された月の範囲でクエリ
        $postCourts = PostCourt::where('elected_date', '>=', $select_and_month_num['month_start'])
                            ->where('elected_date', '<=', $select_and_month_num['month_end'])
                            ->orderBy('elected_date', 'asc')
                            ->orderBy('start_time', 'asc')
                            ->with('user', 'court')
                            ->get();
        
        $users = User::select('id', 'nickname')->get();

        // $postCourts の id を取得
        $postCourtIds = $postCourts->pluck('id')->toArray();

        // $postCourtIds でフィルタリングして PostAttendance を取得
        $attendances = PostAttendance::whereIn('elected_court_id', $postCourtIds)->select('user_id', 'elected_court_id', 'attend_flg')->get();
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
        return view('court.show-court', compact('postCourt'));
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
        $delete_court->delete();
        foreach ($delete_attendance as $d_attendance) {
            $d_attendance->delete();
        }
        return redirect()->route('post-court.index')->with('message', '削除しました');
    }
}
