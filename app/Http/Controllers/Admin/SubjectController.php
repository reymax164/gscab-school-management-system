<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(15);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:subjects',
            'title' => 'required|string|max:255',
            'units' => 'required|integer|min:1|max:10',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:subjects,code,'.$subject->id,
            'title' => 'required|string|max:255',
            'units' => 'required|integer|min:1|max:10',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
