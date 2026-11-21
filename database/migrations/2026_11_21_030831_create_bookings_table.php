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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pickup_address');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            $table->string('destination_address');
            $table->decimal('destination_lat', 10, 7)->nullable();
            $table->decimal('destination_lng', 10, 7)->nullable();
            $table->unsignedInteger('fare')->default(0); // in XAF
            $table->string('status')->default('searching');
            // searching | requested | accepted | arrived | ongoing | completed | cancelled
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('sos_triggered')->default(false);
            $table->timestamp('sos_at')->nullable();
            $table->json('declined_rider_ids')->nullable(); // rider user ids who rejected this booking
            $table->unsignedTinyInteger('passenger_rating')->nullable();
            $table->unsignedTinyInteger('rider_rating')->nullable();
            $table->timestamps();

            $table->index(['status', 'rider_id']);
            $table->index(['passenger_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
