<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pharmacies')) {
            Schema::create('pharmacies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('name');
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                $table->string('address')->nullable();
                $table->string('address_text')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
            return;
        }

        Schema::table('pharmacies', function (Blueprint $table) {
            if (!Schema::hasColumn('pharmacies', 'address')) $table->string('address')->nullable();
            if (!Schema::hasColumn('pharmacies', 'address_text')) $table->string('address_text')->nullable();
            if (!Schema::hasColumn('pharmacies', 'lat')) $table->decimal('lat', 10, 7)->nullable();
            if (!Schema::hasColumn('pharmacies', 'lng')) $table->decimal('lng', 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
