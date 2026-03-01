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
        Schema::create('shave_bath_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->string('client_name');
            $table->string('client_phone');
            $table->integer('num_animals');
            $table->json('animal_type')->nullable(); // Translatable
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shave_bath_booking');
    }
};
