<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $query = User::with(['roles', 'orders']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Role filter
        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function editRoles(User $user){
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit-roles', compact('user','roles','userRoles'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'orders']);
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        // Prevent deleting the last admin
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the last admin user.'
                ]);
            }
            return redirect()->back()->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.users.index')->with('success','User deleted successfully');
    }

    public function updateRoles(Request $request, User $user){
        $request->validate([
            'roles' => 'nullable|array'
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.users.index')->with('success','Roles updated successfully!');
    }
}