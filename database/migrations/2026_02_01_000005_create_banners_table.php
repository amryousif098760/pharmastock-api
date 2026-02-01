<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('image_url')->nullable();
                $table->string('action_type')->nullable();
                $table->string('action_value')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'title')) $table->string('title')->nullable(false);
            if (!Schema::hasColumn('banners', 'subtitle')) $table->string('subtitle')->nullable();
            if (!Schema::hasColumn('banners', 'image_url')) $table->string('image_url')->nullable();
            if (!Schema::hasColumn('banners', 'action_type')) $table->string('action_type')->nullable();
            if (!Schema::hasColumn('banners', 'action_value')) $table->string('action_value')->nullable();

            // Backward-compat: some earlier versions used `sort`.
            if (!Schema::hasColumn('banners', 'sort_order') && Schema::hasColumn('banners', 'sort')) {
                $table->integer('sort_order')->default(0);
            } elseif (!Schema::hasColumn('banners', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }

            // Backward-compat: some earlier versions used `active`.
            if (!Schema::hasColumn('banners', 'is_active') && Schema::hasColumn('banners', 'active')) {
                $table->boolean('is_active')->default(true);
            } elseif (!Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
