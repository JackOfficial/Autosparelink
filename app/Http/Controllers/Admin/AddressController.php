<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display all addresses with their associated users
     */
    public function index()
    {
        $addresses = Address::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.addresses.index', compact('addresses'));
    }

    /**
     * Show form to create address for a specific user
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.addresses.create', compact('users'));
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'street_address' => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'postal_code'    => 'nullable|string',
            'country'        => 'required|string',
            'is_default'     => 'boolean'
        ]);

        DB::transaction(function () use ($request) {
            // If setting as default, remove default status from user's other addresses
            if ($request->is_default) {
                Address::where('user_id', $request->user_id)->update(['is_default' => false]);
            }

            Address::create($request->all());
        });

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address created and linked to user successfully.');
    }

    /**
     * Show single address details
     */
    public function show(string $id)
    {
        $address = Address::with('user')->findOrFail($id);
        return view('admin.addresses.show', compact('address'));
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $address = Address::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('admin.addresses.edit', compact('address', 'users'));
    }

    /**
     * Update address and handle default logic
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'street_address' => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'postal_code'    => 'nullable|string',
            'country'        => 'required|string',
            'is_default'     => 'boolean'
        ]);

        $address = Address::findOrFail($id);

        DB::transaction(function () use ($request, $address) {
            if ($request->is_default) {
                Address::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->update($request->all());
        });

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Delete address
     */
    public function destroy(string $id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return redirect()
            ->route('admin.addresses.index')
            ->with('success', 'Address removed from system.');
    }
}