<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::latest()->paginate(15);

        return view('users.admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('users.admin.classrooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classrooms',
            'capacity' => 'required|integer|min:5|max:500',
            'building_name' => 'nullable|string|max:255',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom created successfully.');
    }

    public function edit(Classroom $classroom)
    {
        return view('users.admin.classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classrooms,name,'.$classroom->id,
            'capacity' => 'required|integer|min:5|max:500',
            'building_name' => 'nullable|string|max:255',
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }
}
