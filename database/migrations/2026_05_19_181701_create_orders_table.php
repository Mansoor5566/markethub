<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->decimal('unit_price', 10, 2); // snapshot at time of purchase
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled'])
                  ->default('pending');
            $table->string('stripe_session_id')->nullable()->unique();
            $table->text('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};