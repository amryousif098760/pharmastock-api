<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'pharmacies' => User::where('role','!=','admin')->count(),
            'pending' => User::where('approval_status','pending')->count(),
            'warehouses' => Warehouse::count(),
            'medicines' => Medicine::count(),
            'orders' => Order::count(),
            'banners' => Banner::count(),
            'categories' => Category::count(),
        ];
        $recentOrders = Order::with('items')->orderByDesc('id')->limit(10)->get();
        $recentUsers = User::orderByDesc('id')->limit(10)->get();
        return view('admin.dashboard', compact('stats','recentOrders','recentUsers'));
    }
}
