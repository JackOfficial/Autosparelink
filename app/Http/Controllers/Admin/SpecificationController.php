<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specifications = Specification::with([
            'variant.vehicleModel.brand',
            'destinations',
            'engineType',
            'transmissionType',
            'driveType'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        // 2. Group dynamically matching your Blade's key pattern: 'Brand|Model|Variant Group'
        $groupedSpecs = $specifications->groupBy(function ($spec) {
            $brand = $spec->variant->vehicleModel->brand->name ?? 'Unknown Brand';
            $model = $spec->variant->vehicleModel->name ?? 'Unknown Model';
            
            // Extracts the variant group or default variant name if your system matches them
            $variantGroupName = $spec->variant->variant_group ?? $spec->variant->name ?? '';

            return "{$brand}|{$model}|{$variantGroupName}";
        });

        return view('admin.specifications.index', compact('groupedSpecs'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
