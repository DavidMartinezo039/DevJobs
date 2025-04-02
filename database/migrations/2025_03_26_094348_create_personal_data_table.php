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
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('about_me')->nullable();
            $table->json('work_permits')->nullable();
            $table->date('birth_date')->nullable();
            //lugar de nacimiento
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->json('nationality')->nullable();
            //contacto
            $table->json('email')->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data');
    }
};
