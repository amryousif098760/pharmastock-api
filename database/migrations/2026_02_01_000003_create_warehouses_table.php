<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('warehouses')) {
            Schema::create('warehouses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('city')->nullable();
                $table->double('lat')->nullable();
                $table->double('lng')->nullable();
                $table->string('address')->nullable();
                $table->string('address_text')->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('warehouses', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouses', 'city')) $table->string('city')->nullable();
            if (!Schema::hasColumn('warehouses', 'lat')) $table->double('lat')->nullable();
            if (!Schema::hasColumn('warehouses', 'lng')) $table->double('lng')->nullable();
            if (!Schema::hasColumn('warehouses', 'address')) $table->string('address')->nullable();
            if (!Schema::hasColumn('warehouses', 'address_text')) $table->string('address_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
