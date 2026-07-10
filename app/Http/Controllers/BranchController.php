<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $branches = $user->isAdmin()
            ? Branch::all()
            : Branch::where('id', $user->employee->department->branch_id)->get();

        return view('branches.index', compact('branches'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        $branch->load('departments.employees.user');

        $recentAssignments = \App\Models\ShiftAssignment::whereHas('employee.department', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->whereBetween('date', [now()->subDays(7), now()])
            ->with(['employee.user', 'shift', 'attendanceRecord'])
            ->orderByDesc('date')
            ->get();

        return view('branches.show', compact('branch', 'recentAssignments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
