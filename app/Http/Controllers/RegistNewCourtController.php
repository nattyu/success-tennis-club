<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistNewCourt;

class RegistNewCourtController extends Controller
{
    public function create() {
        return view('regist.regist-new-court');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'court_name' => 'required',
            'address' => 'required'
        ]);

        $newCourt = RegistNewCourt::create($validated);
        return back()->with('message', '保存しました');
    }
}
