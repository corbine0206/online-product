<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        // Create customer profile
        $customer = Customer::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'active',
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to our store.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $customer = $user->customer;
        return view('customer.dashboard', compact('customer'));
    }

    /**
     * Assign Sales role to a user (for admin use)
     */
    public function assignSalesRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $salesRole = \App\Models\Role::where('name', 'sales')->firstOrFail();
        
        $user->assignRole($salesRole);
        
        return redirect()->back()->with('success', 'Sales role assigned successfully.');
    }

    /**
     * Remove Sales role from a user (for admin use)
     */
    public function removeSalesRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $salesRole = \App\Models\Role::where('name', 'sales')->firstOrFail();
        
        $user->removeRole($salesRole);
        
        return redirect()->back()->with('success', 'Sales role removed successfully.');
    }
}
