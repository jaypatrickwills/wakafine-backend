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
        Schema::create('rider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('bike_type')->default('motorcycle'); // motorcycle | bicycle | tricycle
            $table->string('bike_plate')->nullable();
            $table->string('bike_color')->nullable();
            $table->string('id_document_path')->nullable();
            $table->string('license_path')->nullable();
            $table->string('verification_status')->default('pending'); // pending | approved | rejected
            $table->text('verification_notes')->nullable();
            $table->boolean('is_online')->default(false);
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_profiles');
    }
};
