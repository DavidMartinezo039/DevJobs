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
        // Tabla de documentos de identidad
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Documento de identidad, pasaporte, permiso de residencia
            $table->timestamps();
        });

        // Tabla pivote entre personal_data e identities
        Schema::create('identity_personal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('identity_id')->constrained('identities')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabla de géneros
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Masculino, femenino, otro
            $table->timestamps();
        });

        // Tabla pivote entre personal_data y genders
        Schema::create('gender_personal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('gender_id')->constrained('genders')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabla de tipos de teléfono
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Domicilio, trabajo, móvil, otro
            $table->string('number');
            $table->timestamps();
        });

        // Tabla pivote entre personal_data y phones
        Schema::create('personal_data_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('phone_id')->constrained('phones')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabla de redes sociales
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // X, Facebook, Instagram, etc.
            $table->string('url');
            $table->timestamps();
        });

        // Tabla pivote entre personal_data y social_media
        Schema::create('personal_data_social_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('social_media_id')->constrained('social_media')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data_social_media');
        Schema::dropIfExists('social_media');
        Schema::dropIfExists('personal_data_phones');
        Schema::dropIfExists('phones');
        Schema::dropIfExists('gender_personal_data');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('identity_personal_data');
        Schema::dropIfExists('identities');
    }
};
