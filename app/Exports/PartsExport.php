<?php

namespace App\Exports;
use App\Models\Part;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartsExport implements FromView, ShouldAutoSize
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function view(): View
    {
        return view('admin.parts.exports.table', [
            'parts' => Part::with(['category', 'partBrand', 'fitments.specification.vehicleModel'])->get()
        ]);
    }
}
