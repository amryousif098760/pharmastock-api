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


class DashboardController extends Controller
{

public function index()
{
    $stats = [];

    if (Schema::hasTable('users')) {
        $stats['users'] = User::count();
        $stats['pharmacies'] = Schema::hasColumn('users','role')
            ? User::where('role','!=','admin')->count()
            : 0;
        $stats['pending'] = Schema::hasColumn('users','approval_status')
            ? User::where('approval_status','pending')->count()
            : 0;
    }

    $stats['warehouses'] = Schema::hasTable('warehouses') ? Warehouse::count() : 0;
    $stats['medicines']  = Schema::hasTable('medicines')  ? Medicine::count()  : 0;
    $stats['orders']     = Schema::hasTable('orders')     ? Order::count()     : 0;
    $stats['banners']    = Schema::hasTable('banners')    ? Banner::count()    : 0;
    $stats['categories'] = Schema::hasTable('categories') ? Category::count()  : 0;

    $recentOrders = Schema::hasTable('orders')
        ? Order::orderByDesc('id')->limit(10)->get()
        : collect();

    $recentUsers = Schema::hasTable('users')
        ? User::orderByDesc('id')->limit(10)->get()
        : collect();

    return view('admin.dashboard', compact('stats','recentOrders','recentUsers'));
}

}
