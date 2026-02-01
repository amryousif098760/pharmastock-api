<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SampleSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@dawaplus.local'],
            [
                'name' => 'Admin',
                'phone' => null,
                'password' => Hash::make('Admin@12345'),
                'approval_status' => 'approved',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $wh = Warehouse::firstOrCreate(['name'=>'Main Warehouse'], ['lat'=>30.0444, 'lng'=>31.2357, 'address_text'=>'Cairo']);
        $c1 = Category::firstOrCreate(['name'=>'OTC'], ['name_ar'=>'بدون وصفة','name_en'=>'OTC','icon_url'=>null,'sort_order'=>1,'is_active'=>true]);
        $c2 = Category::firstOrCreate(['name'=>'Prescription'], ['name_ar'=>'بوصفة','name_en'=>'Prescription','icon_url'=>null,'sort_order'=>2,'is_active'=>true]);
        Banner::firstOrCreate(['title'=>'Best prices'], ['subtitle'=>'Trusted pharmacy supply','image_url'=>null,'sort_order'=>1,'is_active'=>true]);
        Banner::firstOrCreate(['title'=>'Fast service'], ['subtitle'=>'Same-day delivery','image_url'=>null,'sort_order'=>2,'is_active'=>true]);
        Medicine::firstOrCreate(['warehouse_id'=>$wh->id, 'name'=>'Paracetamol 500mg'], ['category_id'=>$c1->id,'price'=>12.5,'qty'=>500,'image_url'=>null,'is_featured'=>true]);
        Medicine::firstOrCreate(['warehouse_id'=>$wh->id, 'name'=>'Amoxicillin 500mg'], ['category_id'=>$c2->id,'price'=>45.0,'qty'=>150,'image_url'=>null,'is_featured'=>true]);
    }
}
