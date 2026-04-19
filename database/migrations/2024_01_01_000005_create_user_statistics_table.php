<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->integer('total_items_lent')->default(0);
            $table->integer('total_items_borrowed')->default(0);
            $table->integer('karma_points')->default(0);
            $table->decimal('environmental_impact_score', 8, 2)->default(0.00);
            $table->integer('items_saved_from_purchase')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_statistics');
    }
};
