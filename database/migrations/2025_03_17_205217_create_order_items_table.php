<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('secret_key');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('food_id')->nullable()->constrained('foods')->nullOnDelete();
            $table->foreignId('chef_id')->nullable()->constrained('chefs')->nullOnDelete();
            $table->integer('qty')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->enum('chef_status', ['pending', 'accept', 'complete', 'deliver', 'reject'])->default('pending');
            $table->dateTime('chef_status_updated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};