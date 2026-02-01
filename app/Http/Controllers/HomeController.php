<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Medicine;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $banners = Banner::orderByDesc('id')->limit(5)->get()->map(fn($b)=>[
            'id'=>$b->id,
            'title'=>$b->title,
            'imageUrl'=>$b->image_url,
        ])->values();

        $categories = Category::orderBy('id')->get()->map(fn($c)=>[
            'id'=>$c->id,
            'name'=>$c->name,
        ])->values();

        $featured = Medicine::orderByDesc('id')->limit(12)->get()->map(fn($m)=>[
            'id'=>$m->id,
            'name'=>$m->name,
            'imageUrl'=>$m->image_url,
            'price'=>(float)$m->price,
        ])->values();

        return response()->json(['ok'=>true,'data'=>[
            'banners'=>$banners,
            'categories'=>$categories,
            'featured'=>$featured
        ]], 200);
    }
}
