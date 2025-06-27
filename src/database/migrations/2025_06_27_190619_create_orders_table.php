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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

    $table->string('total', 15)->default('0.00');
    $table->decimal('freight_value', 10, 2)->nullable();
    $table->string('freight_type', 10)->nullable();
    $table->string('coupon_code', 50)->nullable();
    $table->decimal('discount_value', 10, 2)->nullable();

    $table->enum('status', ['draft', 'confirmed', 'paid', 'shipped', 'cancelled'])->default('draft');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
