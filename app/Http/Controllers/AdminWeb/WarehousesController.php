<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $rows = Warehouse::query()
            ->when($q !== '', fn($qr)=>$qr->where('name','like',"%$q%"))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();
        return view('admin.warehouses.index', compact('rows','q'));
    }

    public function create()
    {
        $warehouse = new Warehouse();
        return view('admin.warehouses.form', compact('warehouse')+ ['mode' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'address_text' => ['nullable','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
        ]);
        Warehouse::create($data);
        return redirect()->route('admin.warehouses.index')->with('ok','Saved');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.form', compact('warehouse')+ ['mode' => 'edit']);

    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'address_text' => ['nullable','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
        ]);
        $warehouse->update($data);
        return redirect()->route('admin.warehouses.index')->with('ok','Updated');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')->with('ok','Deleted');
    }
}
