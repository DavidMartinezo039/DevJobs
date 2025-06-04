<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('driving_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->string('vehicle_type');
            $table->integer('max_speed')->nullable();
            $table->float('max_power')->nullable();
            $table->float('power_to_weight')->nullable();
            $table->integer('max_weight')->nullable();
            $table->integer('max_passengers')->nullable();
            $table->integer('min_age');
            $table->boolean('only_god');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_licenses');
    }
};
