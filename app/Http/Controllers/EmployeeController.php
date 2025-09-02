<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index() {
        return view('employees.index');
    }

    public function getEmployees() {
        return response()->json(['data' => Employee::all()]);
    }

    public function store(Request $request) {
        $file = null;
        if ($request->hasFile('profile')) {
            $file = $request->file('profile')->store('profiles', 'public');
        }

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'department' => $request->department,
            
            'profile' => $file
        ]);
        return response()->json(['success'=>true]);
    }

    public function update(Request $request) {
        $emp = Employee::find($request->id);
        $file = $emp->profile;

        if ($request->hasFile('profile')) {
            $file = $request->file('profile')->store('profiles', 'public');
        }

        $emp->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'department' => $request->department,
           
            'profile' => $file
        ]);
        return response()->json(['success'=>true]);
    }

    public function destroy($id) {
        Employee::find($id)->delete();
        return response()->json(['success'=>true]);
    }
}
