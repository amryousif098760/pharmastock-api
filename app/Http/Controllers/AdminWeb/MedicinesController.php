<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MedicinesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        // Support both query param names (old UI used warehouse/category)
        $warehouseId = (int)($request->get('warehouse_id', $request->get('warehouse', 0)));
        $categoryId  = (int)($request->get('category_id',  $request->get('category', 0)));

        $hasFeatured = Schema::hasTable('medicines') && Schema::hasColumn('medicines', 'is_featured');
        $hasCatSort  = Schema::hasTable('categories') && Schema::hasColumn('categories', 'sort_order');

        $rows = Medicine::query()
            ->with(['warehouse','category'])
            ->when($q !== '', fn($qr)=>$qr->where('name','like',"%$q%"))
            ->when($warehouseId>0, fn($qr)=>$qr->where('warehouse_id',$warehouseId))
            ->when($categoryId>0, fn($qr)=>$qr->where('category_id',$categoryId))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $warehouses = Warehouse::orderBy('name')->get();
        $catQuery = Category::query();
        if ($hasCatSort) $catQuery->orderBy('sort_order');
        $categories = $catQuery->orderBy('name')->get();

        return view('admin.medicines.index', compact('rows','q','warehouses','categories','warehouseId','categoryId','hasFeatured'));
    }

    public function create()
    {
        $medicine = new Medicine();
        $warehouses = Warehouse::orderBy('name')->get();
        $catQuery = Category::query();
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'sort_order')) $catQuery->orderBy('sort_order');
        $categories = $catQuery->orderBy('name')->get();

        $hasFeatured = Schema::hasTable('medicines') && Schema::hasColumn('medicines', 'is_featured');
        return view('admin.medicines.form', compact('medicine','warehouses','categories','hasFeatured') + ['mode' => 'create']);

    }

    public function store(Request $request)
    {
        $hasFeatured = Schema::hasTable('medicines') && Schema::hasColumn('medicines', 'is_featured');

        $data = $request->validate([
            'warehouse_id' => ['required','integer'],
            'category_id' => ['nullable','integer'],
            'name' => ['required','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'qty' => ['required','integer','min:0'],
            'image_url' => ['nullable','string','max:2048'],
            'is_featured' => $hasFeatured ? ['nullable'] : ['sometimes'],
        ]);
        if ($hasFeatured) {
            $data['is_featured'] = $request->boolean('is_featured');
        } else {
            unset($data['is_featured']);
        }
        Medicine::create($data);
        return redirect()->route('admin.medicines.index')->with('ok','Saved');
    }

    public function edit(Medicine $medicine)
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $catQuery = Category::query();
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'sort_order')) $catQuery->orderBy('sort_order');
        $categories = $catQuery->orderBy('name')->get();

        $hasFeatured = Schema::hasTable('medicines') && Schema::hasColumn('medicines', 'is_featured');
        return view('admin.medicines.form', compact('medicine','warehouses','categories','hasFeatured') + ['mode' => 'edit']);

    }

    public function update(Request $request, Medicine $medicine)
    {
        $hasFeatured = Schema::hasTable('medicines') && Schema::hasColumn('medicines', 'is_featured');

        $data = $request->validate([
            'warehouse_id' => ['required','integer'],
            'category_id' => ['nullable','integer'],
            'name' => ['required','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'qty' => ['required','integer','min:0'],
            'image_url' => ['nullable','string','max:2048'],
            'is_featured' => $hasFeatured ? ['nullable'] : ['sometimes'],
        ]);
        if ($hasFeatured) {
            $data['is_featured'] = $request->boolean('is_featured');
        } else {
            unset($data['is_featured']);
        }
        $medicine->update($data);
        return redirect()->route('admin.medicines.index')->with('ok','Updated');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('ok','Deleted');
    }
}
