<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicine;

class InventoryController extends Controller
{
    private function dec(Request $r): array { return $r->attributes->get('dec', []); }

    private function gateUser(Request $request)
    {
        $u = $request->user();
        if (!$u) return [null, response()->json(['ok'=>false,'message'=>'Unauthorized'],200)];
        if (is_null($u->email_verified_at)) return [null, response()->json(['ok'=>false,'message'=>'Email not verified'],200)];
        if (($u->approval_status ?? '') !== 'approved') return [null, response()->json(['ok'=>false,'message'=>'Account not approved'],200)];
        return [$u, null];
    }

    public function warehouses(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $rows = Warehouse::orderBy('id')->get()->map(fn($w)=>[
            'id'=>$w->id,
            'name'=>$w->name,
            'lat'=>$w->lat,
            'lng'=>$w->lng,
            'addressText'=>$w->address_text,
        ])->values();

        return response()->json(['ok'=>true,'data'=>$rows],200);
    }

    public function medicines(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $warehouseId = (int)($p['warehouseId'] ?? 0);
        $q = trim((string)($p['q'] ?? ''));
        $page = max((int)($p['page'] ?? 1), 1);
        $perPage = min(max((int)($p['perPage'] ?? 20), 5), 50);

        $qry = Medicine::query();
        if ($warehouseId > 0) $qry->where('warehouse_id', $warehouseId);
        if ($q !== '') $qry->where('name', 'like', "%{$q}%");

        $total = (int)$qry->count();
        $rows = $qry->orderBy('id')->skip(($page-1)*$perPage)->take($perPage)->get()->map(fn($m)=>[
            'id'=>$m->id,
            'warehouseId'=>$m->warehouse_id,
            'categoryId'=>$m->category_id,
            'name'=>$m->name,
            'price'=>(float)$m->price,
            'qty'=>(int)$m->qty,
            'imageUrl'=>$m->image_url,
        ])->values();

        return response()->json(['ok'=>true,'data'=>['items'=>$rows,'page'=>$page,'perPage'=>$perPage,'total'=>$total]],200);
    }

    public function shortages(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'],200);

        $rows = PharmacyMedicine::where('pharmacy_id',$ph->id)->get()->map(fn($pm)=>[
            'medicineId'=>$pm->medicine_id,
            'minStock'=>(int)$pm->min_stock,
            'onHand'=>(int)$pm->on_hand,
            'need'=>max((int)$pm->min_stock - (int)$pm->on_hand, 0),
        ])->values();

        return response()->json(['ok'=>true,'data'=>$rows],200);
    }

    public function updateOnHand(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $medicineId = (int)($p['medicineId'] ?? 0);
        $onHand = (int)($p['onHand'] ?? 0);
        if ($medicineId<=0) return response()->json(['ok'=>false,'message'=>'Invalid payload'],200);

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'],200);

        $pm = PharmacyMedicine::firstOrNew(['pharmacy_id'=>$ph->id,'medicine_id'=>$medicineId]);
        if (!$pm->exists) $pm->min_stock = 0;
        $pm->on_hand = $onHand;
        $pm->save();

        return response()->json(['ok'=>true],200);
    }

    public function setMinStock(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $medicineId = (int)($p['medicineId'] ?? 0);
        $minStock = (int)($p['minStock'] ?? 0);
        if ($medicineId<=0) return response()->json(['ok'=>false,'message'=>'Invalid payload'],200);

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'],200);

        $pm = PharmacyMedicine::firstOrNew(['pharmacy_id'=>$ph->id,'medicine_id'=>$medicineId]);
        if (!$pm->exists) $pm->on_hand = 0;
        $pm->min_stock = $minStock;
        $pm->save();

        return response()->json(['ok'=>true],200);
    }
}
