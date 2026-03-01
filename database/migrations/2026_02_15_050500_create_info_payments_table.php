<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('type'); // 'chat_unlock', 'order', etc.
            $table->text('description')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_payments');
    }
};
