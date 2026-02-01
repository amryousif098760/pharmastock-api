<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('qty')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->index(['warehouse_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
