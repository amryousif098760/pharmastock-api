<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $rows = Category::query()
            ->when($q !== '', fn($qr)=>$qr->where('name','like',"%$q%"))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();
        return view('admin.categories.index', compact('rows','q'));
    }

    public function create()
    {
        $category = new Category();
        return view('admin.categories.form', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'name_ar' => ['nullable','string','max:255'],
            'name_en' => ['nullable','string','max:255'],
            'icon_url' => ['nullable','string','max:2048'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('ok','Saved');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'name_ar' => ['nullable','string','max:255'],
            'name_en' => ['nullable','string','max:255'],
            'icon_url' => ['nullable','string','max:2048'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('ok','Updated');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('ok','Deleted');
    }
}
