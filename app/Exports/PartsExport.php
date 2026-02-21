<?php

namespace App\Exports;
use App\Models\Part;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartsExport implements FromView, ShouldAutoSize
{
    protected $parts;

    /**
     * Create a new class instance.
     */
    public function __construct($parts)
    {
        $this->parts = $parts;
    }

    public function view(): View
    {
        return view('admin.parts.exports.table', [
            'parts' => $this->parts
        ]);
    }
}
