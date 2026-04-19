<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Using pagination is better for "stunning" UI performance as the user base grows
        $users = User::latest('id')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only fetch roles/permissions if the logged-in user is a super-admin
        $roles = auth()->user()->hasRole('super-admin') ? Role::all() : collect();
        $permissions = auth()->user()->hasRole('super-admin') ? Permission::all() : collect();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user in storage.
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

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        // Create user - compatible with Fortify's expectation of a hashed password
        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password ?? Str::random(16)),
            'status'           => $request->status,
            'photo'            => $photoPath,
            'social_providers' => $request->social_providers ?? [],
        ]);

        // Strict Role Assignment: Only super-admin can assign roles other than 'user'
        if (auth()->user()->hasRole('super-admin')) {
            if ($request->filled('roles')) {
                $user->syncRoles($request->roles);
            }
            if ($request->filled('permissions')) {
                $user->syncPermissions($request->permissions);
            }
        } else {
            $user->assignRole('user'); 
        }

        return redirect()->route('admin.users.index')
                         ->with('message', 'User account created successfully.');
    }

    /**
     * Display the specified user.
     */
   public function show(User $user)
{
    // Eager load the shop relationship to save a database query
    $user->load('shop'); 
    
    return view('admin.users.show', compact('user'));
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'password'         => 'nullable|string|confirmed|min:8',
            'status'           => 'required|boolean',
            'photo'            => 'nullable|image|max:2048',
            'roles'            => 'nullable|array',
            'permissions'      => 'nullable|array',
            'social_providers' => 'nullable|array',
        ]);

        $data = $request->only(['name', 'email', 'status']);
        $data['social_providers'] = $request->social_providers ?? [];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        // Security Layer: Only Super Admin can modify Roles and Permissions
        if (auth()->user()->hasRole('super-admin')) {
            // If the roles array is present, sync them. If empty, it removes roles.
            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);
        }

        return redirect()->route('admin.users.index')
                         ->with('message', "User {$user->name} updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent accidental self-deletion
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('message', 'User has been removed from the system.');
    }
}