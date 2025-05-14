<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-auth');
    }

    public function edit()
    {
        $employee = auth()->user();
        return view('profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully. Looking good!');
    }
}