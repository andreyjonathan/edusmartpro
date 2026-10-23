<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('AcademicYears/Index', [
            'academicYears' => AcademicYear::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()->paginate(10),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('AcademicYears/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['is_active']) && $validated['is_active']) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        AcademicYear::create($validated);

        return redirect()->route('academic-years.index')->with('message', 'Academic Year created successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return Inertia::render('AcademicYears/Edit', [
            'academicYear' => $academicYear,
        ]);
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['is_active']) && $validated['is_active']) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        $academicYear->update($validated);

        return redirect()->route('academic-years.index')->with('message', 'Academic Year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()->route('academic-years.index')->with('message', 'Academic Year deleted successfully.');
    }
}
