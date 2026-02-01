<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('status')->default('new');
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
            $table->index(['user_id','warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
