<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique();
            $table->string('full_name', 100);
            $table->string('avatar')->nullable();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('sold_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'full_name', 'avatar']);
        });
    }
};