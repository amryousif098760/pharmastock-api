<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Schema;
use Throwable;

class DashboardController extends Controller
{
    public function index()
    {
        $errors = [];
        $stats = [
            'users' => 0,
            'pharmacies' => 0,
            'pending' => 0,
            'warehouses' => 0,
            'medicines' => 0,
            'orders' => 0,
            'banners' => 0,
            'categories' => 0,
        ];

        $recentOrders = collect();
        $recentUsers  = collect();

        // USERS
        try {
            if (Schema::hasTable('users')) {
                $stats['users'] = User::count();

                if (Schema::hasColumn('users', 'role')) {
                    $stats['pharmacies'] = User::where('role', '!=', 'admin')->count();
                } else {
                    $errors[] = "Missing column users.role";
                }

                if (Schema::hasColumn('users', 'approval_status')) {
                    $stats['pending'] = User::where('approval_status', 'pending')->count();
                } else {
                    $errors[] = "Missing column users.approval_status";
                }

                $recentUsers = User::orderByDesc('id')->limit(10)->get();
            } else {
                $errors[] = "Missing table users";
            }
        } catch (Throwable $e) {
            $errors[] = "Users query failed: ".$e->getMessage();
        }

        try {
            $stats['warehouses'] = Schema::hasTable('warehouses') ? Warehouse::count() : 0;
            if (!Schema::hasTable('warehouses')) $errors[] = "Missing table warehouses";
        } catch (Throwable $e) {
            $errors[] = "Warehouses query failed: ".$e->getMessage();
        }

        try {
            $stats['medicines'] = Schema::hasTable('medicines') ? Medicine::count() : 0;
            if (!Schema::hasTable('medicines')) $errors[] = "Missing table medicines";
        } catch (Throwable $e) {
            $errors[] = "Medicines query failed: ".$e->getMessage();
        }

        try {
            $stats['orders'] = Schema::hasTable('orders') ? Order::count() : 0;
            if (!Schema::hasTable('orders')) $errors[] = "Missing table orders";

            if (Schema::hasTable('orders')) {
                $recentOrders = Order::orderByDesc('id')->limit(10)->get();
            }
        } catch (Throwable $e) {
            $errors[] = "Orders query failed: ".$e->getMessage();
        }

        try {
            $stats['banners'] = Schema::hasTable('banners') ? Banner::count() : 0;
            if (!Schema::hasTable('banners')) $errors[] = "Missing table banners";
        } catch (Throwable $e) {
            $errors[] = "Banners query failed: ".$e->getMessage();
        }

        try {
            $stats['categories'] = Schema::hasTable('categories') ? Category::count() : 0;
            if (!Schema::hasTable('categories')) $errors[] = "Missing table categories";
        } catch (Throwable $e) {
            $errors[] = "Categories query failed: ".$e->getMessage();
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers', 'errors'));
    }
}
