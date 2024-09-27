<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        // $holidays = Holiday::all();
        $holidays = Holiday::orderBy('created_at', 'desc')->get(); 
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
        ]);

        Holiday::create($request->all());

        return redirect()->back()->with('success', 'Holiday created successfully.');
    }

    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        return response()->json($holiday);
    }


    // Phương thức cập nhật ngày nghỉ
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->title = $request->title;
        $holiday->start = $request->start;
        $holiday->end = $request->end;
        $holiday->save();

        return redirect()->route('holiday.index')->with('success', 'Holiday updated successfully.');
    }

    public function delete($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Holiday deleted successfully.');
    }
}
