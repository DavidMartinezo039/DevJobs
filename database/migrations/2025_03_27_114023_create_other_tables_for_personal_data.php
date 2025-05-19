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
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('identity_personal_data', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('identity_id')->constrained('identities')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::table('personal_data', function (Blueprint $table) {
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
        });

        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('personal_data_phones', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('personal_data_id')->constrained('personal_data')->cascadeOnDelete();
            $table->foreignId('phone_id')->constrained('phones')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('personal_data_social_media', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('url');
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
        Schema::dropIfExists('genders');
        Schema::dropIfExists('identity_personal_data');
        Schema::dropIfExists('identities');
    }
};
