<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\PartFitment;
use App\Models\PartPhoto;
use App\Models\Variant;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class PartFitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fitments = PartFitment::with(['part', 'model', 'variant'])->latest()->paginate(20);
        return view('admin.fitments.index', compact('fitments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fitments.create', [
        'parts' => Part::all(),
        'models' => VehicleModel::all(),
        'variants' => Variant::all()
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'part_id' => 'required|exists:parts,id',
        'vehicle_model_id' => 'required|exists:vehicle_models,id',
        'variant_id' => 'nullable|exists:variants,id',
        'status' => 'required|in:active,inactive',
        'year_start' => 'nullable|integer',
        'year_end' => 'nullable|integer',
        'photos.*' => 'nullable|image|max:4096'
    ]);

    $fitment = PartFitment::create($request->only([
        'part_id', 'vehicle_model_id',
        'variant_id', 'status',
        'year_start', 'year_end'
    ]));

    // MULTIPLE PHOTOS HANDLING
    if ($request->hasFile('photos')) {
        foreach ($request->photos as $file) {
            $filename = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/part_photos'), $filename);

            PartPhoto::create([
                'part_id' => $request->part_id,
                'photo_url' => $filename,
                'type' => 'detail'
            ]);
        }
    }

    return redirect()->route('admin.fitments.index')
        ->with('success', 'Fitment added successfully');
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
    $fitment = PartFitment::with('photos')->findOrFail($id);

    return view('admin.fitments.edit', [
        'fitment' => $fitment,
        'parts' => Part::all(),
        'models' => VehicleModel::all(),
        'variants' => Variant::all()
    ]);
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $fitment = PartFitment::findOrFail($id);

    $request->validate([
        'part_id' => 'required|exists:parts,id',
        'vehicle_model_id' => 'required|exists:vehicle_models,id',
        'variant_id' => 'nullable|exists:variants,id',
        'status' => 'required|in:active,inactive',
        'year_start' => 'nullable|integer',
        'year_end' => 'nullable|integer',
        'photos.*' => 'nullable|image|max:4096'
    ]);

    $fitment->update($request->only([
        'part_id', 'vehicle_model_id',
        'variant_id', 'status',
        'year_start', 'year_end'
    ]));

    // Upload new photos
    if ($request->hasFile('photos')) {
        foreach ($request->photos as $file) {
            $filename = time() . '-' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/part_photos'), $filename);

            PartPhoto::create([
                'part_id' => $request->part_id,
                'photo_url' => $filename,
                'type' => 'detail'
            ]);
        }
    }

    return redirect()->route('admin.fitments.index')
        ->with('success', 'Fitment updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
{
    $fitment = PartFitment::findOrFail($id);

    // Delete the photos for this part
    foreach ($fitment->photos as $photo) {
        $file = public_path('uploads/part_photos/' . $photo->photo_url);

        if (file_exists($file)) {
            unlink($file);
        }
        $photo->delete();
    }

    $fitment->delete();

    return redirect()->route('admin.fitments.index')
        ->with('success', 'Fitment deleted successfully.');
}

public function deletePhoto(string $id)
{
    $photo = PartPhoto::findOrFail($id);

    $file = public_path('uploads/part_photos/' . $photo->photo_url);

    if (file_exists($file)) {
        unlink($file);
    }

    $photo->delete();

    return back()->with('success', 'Photo removed.');
}

}
