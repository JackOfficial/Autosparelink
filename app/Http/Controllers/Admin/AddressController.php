<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * Display user's addresses
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();

        return view('admin.addresses.index', compact('addresses'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.addresses.create');
    }

    /**
     * Store new address
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
        ]);

        Auth::user()->addresses()->create($request->all());

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    /**
     * Show single address
     */
    public function show(string $id)
    {
        $address = Auth::user()
            ->addresses()
            ->findOrFail($id);

        return view('admin.addresses.show', compact('address'));
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $address = Auth::user()
            ->addresses()
            ->findOrFail($id);

        return view('admin.addresses.edit', compact('address'));
    }

    /**
     * Update address
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
        ]);

        $address = Auth::user()
            ->addresses()
            ->findOrFail($id);

        $address->update($request->all());

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Delete address
     */
    public function destroy(string $id)
    {
        $address = Auth::user()
            ->addresses()
            ->findOrFail($id);

        $address->delete();

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }
}