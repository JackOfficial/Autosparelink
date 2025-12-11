<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only users with a specific Spatie role
       $users = User::orderBy('id', 'DESC')->get();
       return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|string',   // Admin, Manager, etc.
        ]);

        // Create user WITHOUT password because Fortify handles password flow
        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Assign Spatie Role
        $user->assignRole($request->role);

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

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|string',
        ]);

        $user = User::findOrFail($id);

        // Update user data
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update role (remove old and set new)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
                         ->with('message', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = User::destroy($id);

        return redirect()->back()->with(
            'message',
            $deleted ? 'User has been deleted' : 'User could not be deleted'
        );
    }
}
