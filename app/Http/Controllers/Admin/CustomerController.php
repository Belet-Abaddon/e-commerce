<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    /**
     * Display all customers with pagination (10 per page)
     */
    public function index(Request $request): View
    {
        $query = User::query();
        
        // Search by name, email, or role
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Paginate with 10 items per page
        $customers = $query->latest()->paginate(10);
        
        // Get counts for each role
        $roleCounts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'user' => User::where('role', 'user')->count(),
        ];
        
        return view('admin.customers.index', compact('customers', 'roleCounts'));
    }
    
    /**
     * Show customer details
     */
    public function show(int $id): View
    {
        $customer = User::findOrFail($id);
        
        return view('admin.customers.show', compact('customer'));
    }
    
    /**
     * Update customer role
     */
    public function updateRole(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);
        
        $customer = User::findOrFail($id);
        
        // Prevent admin from changing their own role
        if ($customer->id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role!');
        }
        
        $customer->role = $request->role;
        $customer->save();
        
        return redirect()->back()->with('success', 'Customer role updated successfully!');
    }
}