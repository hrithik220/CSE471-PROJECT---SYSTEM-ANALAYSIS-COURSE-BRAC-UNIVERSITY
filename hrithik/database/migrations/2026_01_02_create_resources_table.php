<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('condition', ['new', 'good', 'fair', 'poor']);
            $table->enum('availability_status', ['available', 'borrowed', 'unavailable'])->default('available');
            $table->enum('sharing_type', ['free', 'exchange']);
            $table->date('availability_until')->nullable();
            $table->json('image_paths')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            // Module 2 — Google Maps pickup location
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            $table->string('pickup_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
