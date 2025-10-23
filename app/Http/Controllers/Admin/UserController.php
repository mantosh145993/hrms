<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'doj' => 'required',
            'dob' => 'required',
            'designation' => 'required',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'doj' => $request->doj,
            'dob'=> $request->dob,
            'designation' => $request->designation
        ]);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'role' => 'required',
            'doc' => '',
            'doj' => '',
            'dob' => '',
            'designation' => '',
        ]);
        $data = $request->only(['name','email','role','doc','doj','dob','designation']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
