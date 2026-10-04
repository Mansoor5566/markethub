<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load('roles');
        $listings = $user->listings()->latest()->take(5)->get();
        $orders   = $user->orders()->with('listing')->latest()->take(5)->get();
        $reviews  = $user->reviews()->with('listing')->latest()->take(5)->get();

        return view('admin.users.show', compact('user', 'listings', 'orders', 'reviews'));
    }

    public function ban(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', $user->name . ' has been banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', $user->name . ' has been unbanned.');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:buyer,seller,admin'],
        ]);

        $user->syncRoles([$request->role]);
        return back()->with('success', 'Role updated to ' . $request->role . '.');
    }
}