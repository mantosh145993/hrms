<?php

namespace App\Http\Controllers\admin;

use App\Models\{User, Task, Feedback};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    protected $feedback;
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::with(['employee', 'givenBy'])->get();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::with('user')->get();
        return view('admin.feedback.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'employee_id' => 'required',
            'title' => 'required|string|max:255',
            'message' => 'required',
            'type' => 'required',
            'given_by' => 'required'
        ]);
        Feedback::create($validated);
        return redirect()->route('feedbacks.create')->with('success', 'Feedbacks added successfully.');
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
    public function edit(Feedback $feedback)
    {
        // Get all employees for the dropdown
        $employees = User::all();

        // Eager load relationships if needed
        $feedback->load(['employee', 'givenBy']);

        return view('admin.feedback.edit', compact('feedback', 'employees'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        // Validate request
        $validated = $request->validate([
            'employee_id' => 'required',
            'title'       => 'required|string|max:255',
            'message'     => 'required',
            'type'        => 'required',
            'given_by'    => 'required',
        ]);

        // Update the feedback
        $feedback->update($validated);

        // Redirect back with success message
        return redirect()->route('feedbacks.edit', $feedback->id)
            ->with('success', 'Feedback updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('feedbacks.index')->with('success','Feedback deleted!');
    }
}
