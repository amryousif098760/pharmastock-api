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
        $u = $request->attributes->get('auth_user');
        if (!$u) return [null, response()->json(['ok'=>false,'message'=>'Unauthorized'],200)];
        if (is_null($u->email_verified_at)) return [null, response()->json(['ok'=>false,'message'=>'Email not verified'],200)];
        if ($u->approval_status !== 'approved') return [null, response()->json(['ok'=>false,'message'=>'Account not approved'],200)];
        return [$u, null];
    }

    public function warehouses(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $list = Warehouse::withCount(['medicines as availableCount' => function($q){
            $q->where('qty','>',0);
        }])->get()->map(fn($w)=>[
            'id'=>$w->id,
            'name'=>$w->name,
            'city'=>$w->city,
            'availableCount'=>(int)$w->availableCount,
        ])->values();

        return response()->json(['ok'=>true,'data'=>$list], 200);
    }

    public function medicines(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $warehouseId = (int)($p['warehouseId'] ?? 0);
        if ($warehouseId<=0) return response()->json(['ok'=>false,'message'=>'warehouseId required'], 200);

        $list = Medicine::where('warehouse_id',$warehouseId)->get()->map(fn($m)=>[
            'id'=>$m->id,
            'name'=>$m->name,
            'form'=>$m->form,
            'qty'=>(int)$m->qty,
            'price'=>(float)$m->price,
        ])->values();

        return response()->json(['ok'=>true,'data'=>$list], 200);
    }

    public function updateOnHand(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $medicineId = (int)($p['medicineId'] ?? 0);
        $onHand = (int)($p['onHand'] ?? 0);

        if ($medicineId<=0 || $onHand<0) return response()->json(['ok'=>false,'message'=>'Invalid input'], 200);

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'], 200);

        $row = PharmacyMedicine::firstOrCreate(
            ['pharmacy_id'=>$ph->id,'medicine_id'=>$medicineId],
            ['on_hand'=>0,'min_stock'=>0]
        );

        $row->on_hand = $onHand;
        $row->save();

        return response()->json(['ok'=>true,'message'=>'Updated'], 200);
    }

    public function setMinStock(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $medicineId = (int)($p['medicineId'] ?? 0);
        $minStock = (int)($p['minStock'] ?? 0);

        if ($medicineId<=0 || $minStock<0) return response()->json(['ok'=>false,'message'=>'Invalid input'], 200);

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>false,'message'=>'No pharmacy'], 200);

        $row = PharmacyMedicine::firstOrCreate(
            ['pharmacy_id'=>$ph->id,'medicine_id'=>$medicineId],
            ['on_hand'=>0,'min_stock'=>0]
        );

        $row->min_stock = $minStock;
        $row->save();

        return response()->json(['ok'=>true,'message'=>'Updated'], 200);
    }

    public function shortages(Request $request)
    {
        [$u,$err] = $this->gateUser($request);
        if ($err) return $err;

        $ph = Pharmacy::where('user_id',$u->id)->first();
        if (!$ph) return response()->json(['ok'=>true,'data'=>[]], 200);

        $rows = PharmacyMedicine::where('pharmacy_id',$ph->id)
            ->with('medicine')
            ->get()
            ->map(function($pm){
                $need = max((int)$pm->min_stock - (int)$pm->on_hand, 0);
                return [
                    'medicineId'=>$pm->medicine_id,
                    'name'=>$pm->medicine?->name ?? '',
                    'form'=>$pm->medicine?->form ?? '',
                    'onHand'=>(int)$pm->on_hand,
                    'minStock'=>(int)$pm->min_stock,
                    'need'=>$need,
                ];
            })
            ->filter(fn($x)=>$x['need']>0)
            ->values();

        return response()->json(['ok'=>true,'data'=>$rows], 200);
    }
}
