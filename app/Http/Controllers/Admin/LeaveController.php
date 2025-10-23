<?php

namespace App\Http\Controllers\Admin;

use App\Models\Leave;
use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $leaves = Leave::with('LeaveType')->where('user_id', $user_id)->get();
        $leaveTypes = LeaveType::all();
        return view("employee.applyLeave", compact('leaveTypes', 'leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:255',
            'reason' => 'required|string',
        ]);
        Leave::create($validatedData);
        return redirect()->route('leaves.index')->with('success', 'Leave applied successfully.');
    }

    public function updateField(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        // Update only allowed fields
        $allowedFields = ['start_date', 'end_date', 'reason', 'status', 'is_paid_leave'];

        foreach ($request->all() as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $leave->$field = $value;
            }
        }

        $leave->save();

        // Optional reload after approval (to lock editing)
        $reload = $request->has('status') && $request->status === 'approved';

        return response()->json([
            'message' => 'Leave updated successfully!',
            'reload' => $reload
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
