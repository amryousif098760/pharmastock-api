<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('medicines')) {
            Schema::create('medicines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('warehouse_id');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('name');
                $table->string('form')->nullable();
                $table->integer('qty')->default(0);
                $table->decimal('price', 12, 2)->default(0);
                $table->string('image_url')->nullable();
                $table->timestamps();

                $table->index(['warehouse_id', 'category_id']);
                $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            });
            return;
        }

        Schema::table('medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('medicines', 'category_id')) $table->unsignedBigInteger('category_id')->nullable()->after('warehouse_id');
            if (!Schema::hasColumn('medicines', 'form')) $table->string('form')->nullable()->after('name');
            if (!Schema::hasColumn('medicines', 'image_url')) $table->string('image_url')->nullable();
            if (!Schema::hasColumn('medicines', 'price')) $table->decimal('price', 12, 2)->default(0);
            if (!Schema::hasColumn('medicines', 'qty')) $table->integer('qty')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
