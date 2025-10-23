<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeaveType;
use App\Models\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leave_types = LeaveType::latest()->paginate(10);
        return view('admin.leave_types.index', compact('leave_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leave_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'max_days' => 'required'
        ]);

        LeaveType::create($request->all());
        return redirect()->route('leave-types.index')->with('success', 'Leave Type created successfully.');
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
    public function edit(LeaveType $leave_type)
    {
        return view('admin.leave_types.edit', compact('leave_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leave_type)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'max_days' => 'required',
            'paid_status' => 'numeric'
        ]);
        $leave_type->update($request->all());
        return redirect()->route('leave-types.index')->with('success', 'Leave Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leave_type)
    {
        $leave_type->delete();
        return redirect()->route('leave-types.index')->with('success', 'Leave Type deleted successfully.');
    }

    public function getAppliedLeave()
    {
        $leaves = Leave::with('user','LeaveType')->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function updateLeaveStatus(Request $request)
    {
        $leave = Leave::findOrFail($request->id);

        if ($request->field === 'status') {
            $leave->status = $request->value;
        } elseif ($request->field === 'is_paid_leave') {
            $leave->is_paid_leave = $request->value;
        }
        $leave->save();
        return response()->json(['message' => 'Leave status updated successfully!']);
    }

    public function updateReason(Request $request)
    {
        $leave = Leave::find($request->id);

        if (!$leave) {
            return response()->json(['message' => 'Leave not found'], 404);
        }

        $leave->reason = $request->reason;
        $leave->save();

        return response()->json(['message' => 'Reason updated successfully!']);
    }
}
