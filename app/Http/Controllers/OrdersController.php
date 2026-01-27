<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicine;

class OrdersController extends Controller
{
    private function dec(Request $r): array { return $r->attributes->get('dec', []); }

    private function gateUser(Request $request)
    {
        $u = $request->attributes->get('auth_user');
        if (!$u) return [null, response()->json(['ok'=>false,'message'=>'Unauthorized'],200)];
        if (is_null($u->email_verified_at)) return [null, response()->json(['ok'=>false,'message'=>'Email not verified'],200)];
        if ($u->approval_status !== 'approved') return [null, response()->json(['ok'=>false,'message'=>'Account not approved'],200)];
        return [$u, null];
    }

    public function create(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $warehouseId = (int)($p['warehouseId'] ?? 0);
        $items = $p['items'] ?? [];

        if ($warehouseId<=0 || !is_array($items) || count($items)===0) {
            return response()->json(['ok'=>false,'message'=>'Invalid payload'], 200);
        }

        return DB::transaction(function() use ($u, $warehouseId, $items) {
            $order = Order::create([
                'user_id'=>$u->id,
                'warehouse_id'=>$warehouseId,
                'status'=>'new',
                'total'=>0
            ]);

            $total = 0.0;

            foreach ($items as $it) {
                $mid = (int)($it['medicineId'] ?? 0);
                $qty = (int)($it['qty'] ?? 0);
                if ($mid<=0 || $qty<=0) continue;

                $m = Medicine::where('id',$mid)->where('warehouse_id',$warehouseId)->lockForUpdate()->first();
                if (!$m) throw new \RuntimeException("Medicine not found");
                if ($m->qty < $qty) throw new \RuntimeException("Not enough stock: {$m->name}");

                $price = (float)$m->price;
                $line = $price * $qty;

                OrderItem::create([
                    'order_id'=>$order->id,
                    'medicine_id'=>$m->id,
                    'qty'=>$qty,
                    'price'=>$price,
                    'line_total'=>$line
                ]);

                $m->qty -= $qty;
                $m->save();

                $total += $line;
            }

            if ($total <= 0) throw new \RuntimeException("Empty order");

            $order->total = $total;
            $order->save();

            return response()->json(['ok'=>true,'data'=>['orderId'=>$order->id,'total'=>$total]], 200);
        });
    }

    public function createFromShortages(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $warehouseId = (int)($p['warehouseId'] ?? 0);
        if ($warehouseId<=0) return response()->json(['ok'=>false,'message'=>'warehouseId required'], 200);

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'], 200);

        $shortRows = PharmacyMedicine::where('pharmacy_id',$ph->id)->get();

        $items = [];
        foreach ($shortRows as $pm) {
            $need = max((int)$pm->min_stock - (int)$pm->on_hand, 0);
            if ($need<=0) continue;

            $m = Medicine::where('id',$pm->medicine_id)->where('warehouse_id',$warehouseId)->first();
            if (!$m) continue;

            $items[] = ['medicineId'=>$m->id, 'qty'=>$need];
        }

        if (count($items)===0) {
            return response()->json(['ok'=>false,'message'=>'No shortages available in this warehouse'], 200);
        }

        $request->attributes->set('dec', array_merge($p, [
            'warehouseId'=>$warehouseId,
            'items'=>$items
        ]));

        return $this->create($request);
    }

    public function list(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $rows = Order::where('user_id',$u->id)->orderByDesc('id')->get()->map(fn($o)=>[
            'id'=>$o->id,
            'warehouseId'=>$o->warehouse_id,
            'status'=>$o->status,
            'total'=>(float)$o->total,
            'createdAt'=>$o->created_at?->toDateTimeString(),
        ])->values();

        return response()->json(['ok'=>true,'data'=>$rows], 200);
    }

    public function details(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $orderId = (int)($p['orderId'] ?? 0);

        $o = Order::where('id',$orderId)->where('user_id',$u->id)->first();
        if (!$o) return response()->json(['ok'=>false,'message'=>'Not found'], 200);

        $items = OrderItem::where('order_id',$o->id)->get()->map(fn($i)=>[
            'medicineId'=>$i->medicine_id,
            'qty'=>(int)$i->qty,
            'price'=>(float)$i->price,
            'lineTotal'=>(float)$i->line_total,
        ])->values();

        return response()->json(['ok'=>true,'data'=>[
            'id'=>$o->id,
            'warehouseId'=>$o->warehouse_id,
            'status'=>$o->status,
            'total'=>(float)$o->total,
            'items'=>$items
        ]], 200);
    }
}
