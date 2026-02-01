<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pharmacy_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('medicine_id');
            $table->integer('on_hand')->default(0);
            $table->integer('min_stock')->default(0);
            $table->timestamps();
            $table->unique(['pharmacy_id','medicine_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_medicines');
    }
};
