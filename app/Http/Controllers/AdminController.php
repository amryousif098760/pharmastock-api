<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Medicine;

class AdminController extends Controller
{
    private function dec(Request $r): array { return $r->attributes->get('dec', []); }

    private function adminOrFail(Request $r)
    {
        $u = $r->attributes->get('auth_user');
        if (!$u || $u->role !== 'admin') {
            return [null, response()->json(['ok'=>false,'message'=>'Admin only'], 200)];
        }
        return [$u, null];
    }

    public function createWarehouse(Request $request)
    {
        [$_,$err] = $this->adminOrFail($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $name = trim($p['name'] ?? '');
        $city = trim($p['city'] ?? '');

        if (!$name) return response()->json(['ok'=>false,'message'=>'name required'], 200);

        $w = Warehouse::create(['name'=>$name,'city'=>$city]);
        return response()->json(['ok'=>true,'data'=>['id'=>$w->id]], 200);
    }

    public function createMedicine(Request $request)
    {
        [$_,$err] = $this->adminOrFail($request);
        if ($err) return $err;

        $p = $this->dec($request);

        $wid = (int)($p['warehouseId'] ?? 0);
        $name = trim($p['name'] ?? '');
        $form = trim($p['form'] ?? '');
        $qty = (int)($p['qty'] ?? 0);
        $price = (float)($p['price'] ?? 0);

        if ($wid<=0 || !$name) return response()->json(['ok'=>false,'message'=>'Invalid input'], 200);

        $m = Medicine::create([
            'warehouse_id'=>$wid,
            'name'=>$name,
            'form'=>$form,
            'qty'=>$qty,
            'price'=>$price
        ]);

        return response()->json(['ok'=>true,'data'=>['id'=>$m->id]], 200);
    }

    public function updateMedicine(Request $request)
    {
        [$_,$err] = $this->adminOrFail($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $id = (int)($p['id'] ?? 0);

        $m = Medicine::find($id);
        if (!$m) return response()->json(['ok'=>false,'message'=>'Not found'], 200);

        foreach (['name','form'] as $f) {
            if (array_key_exists($f, $p)) $m->$f = (string)$p[$f];
        }
        if (array_key_exists('qty',$p)) $m->qty = (int)$p['qty'];
        if (array_key_exists('price',$p)) $m->price = (float)$p['price'];

        $m->save();
        return response()->json(['ok'=>true,'message'=>'Updated'], 200);
    }

    public function deleteMedicine(Request $request)
    {
        [$_,$err] = $this->adminOrFail($request);
        if ($err) return $err;

        $p = $this->dec($request);
        $id = (int)($p['id'] ?? 0);

        $m = Medicine::find($id);
        if (!$m) return response()->json(['ok'=>false,'message'=>'Not found'], 200);

        $m->delete();
        return response()->json(['ok'=>true,'message'=>'Deleted'], 200);
    }
}
