<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended('admin/dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have admin access.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $recentOrders = Order::with('product')->latest()->take(5)->get();

        // 1. Monthly Revenue (Last 6 months)
        $revenueData = Order::where('payment_status', 'paid')
            ->selectRaw('SUM(total_amount) as total, MONTHNAME(created_at) as month')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        // 2. Order Status Distribution
        $statusData = Order::selectRaw('COUNT(*) as count, status')
            ->groupBy('status')
            ->get();

        // 3. Top Categories
        $categoryData = ProductCategory::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        // 4. User Registrations (Last 6 months)
        $userData = User::selectRaw('COUNT(*) as count, MONTHNAME(created_at) as month')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalOrders', 'totalProducts', 'totalRevenue', 'recentOrders',
            'revenueData', 'statusData', 'categoryData', 'userData'
        ));
    }

    public function setLanguage($locale)
    {
        if (!in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $locale;
            $user->save();
        }

        session(['locale' => $locale]);

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
