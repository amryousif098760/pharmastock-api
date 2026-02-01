<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $rows = Banner::query()
            ->when($q !== '', fn($qr)=>$qr->where('title','like',"%$q%"))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();
        return view('admin.banners.index', compact('rows','q'));
    }

    public function create()
    {
        $banner = new Banner();
        return view('admin.banners.form', compact('banner'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subtitle' => ['nullable','string','max:255'],
            'image_url' => ['nullable','string','max:2048'],
            'action_type' => ['nullable','string','max:50'],
            'action_value' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('ok','Saved');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subtitle' => ['nullable','string','max:255'],
            'image_url' => ['nullable','string','max:2048'],
            'action_type' => ['nullable','string','max:50'],
            'action_value' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('ok','Updated');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('ok','Deleted');
    }
}
