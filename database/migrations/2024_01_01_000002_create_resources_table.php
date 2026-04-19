<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->enum('type', ['free_lending', 'exchange']);
            $table->string('photo')->nullable();
            $table->date('availability_start');
            $table->date('availability_end');
            $table->enum('status', ['available', 'borrowed', 'unavailable'])->default('available');
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->string('location_name')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->text('ocr_text')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('resources'); }
};
