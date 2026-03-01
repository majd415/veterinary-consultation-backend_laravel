<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,vet',
            'is_admin' => 'nullable|boolean',
            'admin_role' => 'nullable|in:super_admin,accountant,data_entry',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->is_admin ?? false,
            'admin_role' => $request->admin_role,
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,vet',
            'is_admin' => 'nullable|boolean',
            'admin_role' => 'nullable|in:super_admin,accountant,data_entry',
        ]);

        $data = $request->only(['name', 'email', 'role', 'is_admin', 'admin_role']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
