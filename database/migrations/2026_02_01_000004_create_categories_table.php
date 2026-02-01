<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_ar')->nullable();
                $table->string('name_en')->nullable();
                $table->string('icon_url')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name')) $table->string('name');
            if (!Schema::hasColumn('categories', 'name_ar')) $table->string('name_ar')->nullable();
            if (!Schema::hasColumn('categories', 'name_en')) $table->string('name_en')->nullable();
            if (!Schema::hasColumn('categories', 'icon_url')) $table->string('icon_url')->nullable();

            // Backward-compat: some earlier versions used `sort`.
            if (!Schema::hasColumn('categories', 'sort_order') && Schema::hasColumn('categories', 'sort')) {
                // add new column, old data can be migrated manually later.
                $table->integer('sort_order')->default(0);
            } elseif (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }

            // Backward-compat: some earlier versions used `active`.
            if (!Schema::hasColumn('categories', 'is_active') && Schema::hasColumn('categories', 'active')) {
                $table->boolean('is_active')->default(true);
            } elseif (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
