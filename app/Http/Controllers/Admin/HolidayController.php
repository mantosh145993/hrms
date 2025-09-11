<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date','asc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'title' => 'required|string|max:255',
            'is_paid' => 'boolean',
            'end_date'=>''
        ]);

        Holiday::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    public function show(Holiday $holiday)
    {
        return view('admin.holidays.show', compact('holiday'));
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date,'.$holiday->id,
            'title' => 'required|string|max:255',
            'is_paid' => 'boolean',
            'end_date'=>''
        ]);

        $holiday->update($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
