<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Medicine;

class AdminController extends Controller
{
    private function dec(Request $r): array { return $r->attributes->get('dec', []); }

    public function createWarehouse(Request $request)
    {
        $p = $this->dec($request);
        $name = trim((string)($p['name'] ?? ''));
        if ($name === '') return response()->json(['ok'=>false,'message'=>'name required'], 200);
        $w = Warehouse::create([
            'name'=>$name,
            'lat'=>(float)($p['lat'] ?? 0),
            'lng'=>(float)($p['lng'] ?? 0),
            'address_text'=>trim((string)($p['addressText'] ?? ''))
        ]);
        return response()->json(['ok'=>true,'data'=>['id'=>$w->id]], 200);
    }

    public function createMedicine(Request $request)
    {
        $p = $this->dec($request);
        $warehouseId = (int)($p['warehouseId'] ?? 0);
        $name = trim((string)($p['name'] ?? ''));
        if ($warehouseId<=0 || $name==='') return response()->json(['ok'=>false,'message'=>'Invalid payload'], 200);

        $m = Medicine::create([
            'warehouse_id'=>$warehouseId,
            'category_id'=>(int)($p['categoryId'] ?? 0) ?: null,
            'name'=>$name,
            'price'=>(float)($p['price'] ?? 0),
            'qty'=>(int)($p['qty'] ?? 0),
            'image_url'=>trim((string)($p['imageUrl'] ?? ''))
        ]);

        return response()->json(['ok'=>true,'data'=>['id'=>$m->id]], 200);
    }

    public function updateMedicine(Request $request)
    {
        $p = $this->dec($request);
        $id = (int)($p['id'] ?? 0);
        $m = Medicine::where('id',$id)->first();
        if (!$m) return response()->json(['ok'=>false,'message'=>'Not found'], 200);

        foreach (['name','imageUrl'] as $k) {
            if (array_key_exists($k, $p)) {
                $val = trim((string)$p[$k]);
                if ($k==='imageUrl') $m->image_url = $val; else $m->name = $val;
            }
        }
        if (array_key_exists('price',$p)) $m->price = (float)$p['price'];
        if (array_key_exists('qty',$p)) $m->qty = (int)$p['qty'];
        if (array_key_exists('categoryId',$p)) $m->category_id = (int)$p['categoryId'] ?: null;
        $m->save();

        return response()->json(['ok'=>true], 200);
    }

    public function deleteMedicine(Request $request)
    {
        $p = $this->dec($request);
        $id = (int)($p['id'] ?? 0);
        Medicine::where('id',$id)->delete();
        return response()->json(['ok'=>true], 200);
    }
}
