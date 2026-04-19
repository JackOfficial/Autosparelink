<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
// use Illuminate\Support - DB;

class CommissionController extends Controller
{
    /**
     * Display a listing of commission rates.
     */
    public function index()
    {
        $commissions = Commission::latest()->paginate(10);
        return view('admin.commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new commission.
     */
    public function create()
    {
        return view('admin.commissions.create');
    }

    /**
     * Store a newly created commission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // If this new rate is active, deactivate all previous rates
        if ($request->has('is_active')) {
            Commission::where('is_active', true)->update(['is_active' => false]);
        }

        Commission::create($request->all());

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission rate created successfully.');
    }

    /**
     * Show the form for editing the specified commission.
     */
    public function edit(Commission $commission)
    {
        return view('admin.commissions.edit', compact('commission'));
    }

    /**
     * Update the specified commission.
     */
    public function update(Request $request, Commission $commission)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // If setting this rate to active, deactivate others
        if ($request->is_active && !$commission->is_active) {
            Commission::where('id', '!=', $commission->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $commission->update($request->all());

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission rate updated.');
    }

    /**
     * Remove the specified commission.
     */
    public function destroy(Commission $commission)
    {
        if ($commission->is_active) {
            return back()->with('error', 'Cannot delete the currently active commission rate.');
        }

        $commission->delete();
        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission rate removed.');
    }
}