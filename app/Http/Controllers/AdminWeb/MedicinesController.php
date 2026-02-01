<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class MedicinesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $warehouseId = (int)$request->get('warehouse_id',0);
        $categoryId = (int)$request->get('category_id',0);

        $rows = Medicine::query()
            ->with(['warehouse','category'])
            ->when($q !== '', fn($qr)=>$qr->where('name','like',"%$q%"))
            ->when($warehouseId>0, fn($qr)=>$qr->where('warehouse_id',$warehouseId))
            ->when($categoryId>0, fn($qr)=>$qr->where('category_id',$categoryId))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $warehouses = Warehouse::orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.medicines.index', compact('rows','q','warehouses','categories','warehouseId','categoryId'));
    }

    public function create()
    {
        $medicine = new Medicine();
        $warehouses = Warehouse::orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.medicines.form', compact('medicine','warehouses','categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => ['required','integer'],
            'category_id' => ['nullable','integer'],
            'name' => ['required','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'qty' => ['required','integer','min:0'],
            'image_url' => ['nullable','string','max:2048'],
            'is_featured' => ['nullable'],
        ]);
        $data['is_featured'] = $request->boolean('is_featured');
        Medicine::create($data);
        return redirect()->route('admin.medicines.index')->with('ok','Saved');
    }

    public function edit(Medicine $medicine)
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.medicines.form', compact('medicine','warehouses','categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'warehouse_id' => ['required','integer'],
            'category_id' => ['nullable','integer'],
            'name' => ['required','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'qty' => ['required','integer','min:0'],
            'image_url' => ['nullable','string','max:2048'],
            'is_featured' => ['nullable'],
        ]);
        $data['is_featured'] = $request->boolean('is_featured');
        $medicine->update($data);
        return redirect()->route('admin.medicines.index')->with('ok','Updated');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('ok','Deleted');
    }
}
