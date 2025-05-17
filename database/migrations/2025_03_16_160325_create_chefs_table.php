<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('chefs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('otp')->nullable();
            $table->boolean('is_verify')->default(0);
            $table->string('image')->nullable();
            $table->decimal('wallet', 8, 2)->default(0.00);
            $table->integer('countSubscribe')->default(0);
            $table->text('bio')->nullable();
            $table->integer('totalOrder')->default(0);
            $table->string('fcm_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('chefs');
    }
};