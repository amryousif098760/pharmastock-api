<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $status = trim((string)$request->get('status',''));
        $rows = Order::query()
            ->with(['user','warehouse'])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('id',$q)
                    ->orWhereHas('user', fn($u)=>$u->where('email','like',"%$q%"))
                    ->orWhereHas('user', fn($u)=>$u->where('name','like',"%$q%"));
            })
            ->when($status !== '', fn($qr)=>$qr->where('status',$status))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $statuses = ['new','processing','delivered','cancelled'];
        return view('admin.orders.index', compact('rows','q','status','statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user','warehouse','items.medicine']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required','string','max:30'],
        ]);
        $order->status = $data['status'];
        $order->save();
        return redirect()->route('admin.orders.show',$order)->with('ok','Updated');
    }
}
