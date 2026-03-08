<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'nullable|string|confirmed|min:8',
            'status'           => 'required|boolean',
            'photo'            => 'nullable|image|max:2048',
            'roles'            => 'nullable|array',
            'permissions'      => 'nullable|array',
            'social_providers' => 'nullable|array',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        // Create user
        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => $request->password ? Hash::make($request->password) : Hash::make(str()->random(16)),
            'status'           => $request->status,
            'photo'            => $photoPath,
            'social_providers' => $request->social_providers ?? [],
        ]);

        // Assign roles
        if (auth()->user()->hasRole('super-admin') && $request->filled('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->assignRole('user'); // default role
        }

        // Assign permissions if super-admin
        if (auth()->user()->hasRole('super-admin') && $request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.users.index')
                         ->with('message', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $id,
            'password'         => 'nullable|string|confirmed|min:8',
            'status'           => 'required|boolean',
            'photo'            => 'nullable|image|max:2048',
            'roles'            => 'nullable|array',
            'permissions'      => 'nullable|array',
            'social_providers' => 'nullable|array',
        ]);

        $data = $request->only(['name', 'email', 'status']);
        $data['social_providers'] = $request->social_providers ?? [];

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        // Sync roles & permissions if super-admin
        if (auth()->user()->hasRole('super-admin')) {
            if ($request->filled('roles')) {
                $user->syncRoles($request->roles);
            }
            if ($request->filled('permissions')) {
                $user->syncPermissions($request->permissions);
            }
        }

        return redirect()->route('admin.users.index')
                         ->with('message', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete local photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->back()->with('message', 'User has been deleted successfully.');
    }
}
