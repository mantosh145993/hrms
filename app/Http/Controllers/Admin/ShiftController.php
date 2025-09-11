<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::latest()->paginate(10);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'start_time'            => 'required',
            'end_time'              => 'required',
            'grace_minutes'         => 'nullable|integer',
            'half_day_after_minutes'=> 'nullable|integer',
            'workday_minutes'       => 'nullable|integer',
        ]);

        Shift::create($request->all());

        return redirect()->route('shifts.index')
                         ->with('success', 'Shift created successfully.');
    }

    public function show(Shift $shift)
    {
        return view('admin.shifts.show', compact('shift'));
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'start_time'            => 'required',
            'end_time'              => 'required',
            'grace_minutes'         => 'nullable|integer',
            'half_day_after_minutes'=> 'nullable|integer',
            'workday_minutes'       => 'nullable|integer',
        ]);

        $shift->update($request->all());

        return redirect()->route('shifts.index')
                         ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('shifts.index')
                         ->with('success', 'Shift deleted successfully.');
    }
}
