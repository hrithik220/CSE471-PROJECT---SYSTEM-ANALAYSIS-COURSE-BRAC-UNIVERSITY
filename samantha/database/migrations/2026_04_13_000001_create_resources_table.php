<?php
// ── 2026_04_13_000001_create_resources_table.php ──────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title', 120);
            $table->enum('category', ['textbook', 'notes', 'lab_equipment', 'electronics', 'stationery', 'other']);
            $table->enum('condition', ['new', 'good', 'fair', 'poor']);
            $table->text('description');

            $table->enum('sharing_type', ['free_lending', 'exchange', 'both'])->default('free_lending');
            $table->string('exchange_note', 200)->nullable();

            $table->date('available_from');
            $table->date('available_until');
            $table->unsignedTinyInteger('max_borrow_days')->default(7);
            $table->string('pickup_location', 200)->nullable();

            $table->string('course_code', 30)->nullable();
            $table->string('department', 100)->nullable();
            $table->json('tags')->nullable();
            $table->json('photos')->nullable();

            $table->enum('status', ['available', 'borrowed', 'unavailable'])->default('available');
            $table->timestamps();

            $table->index(['category', 'status']);
            $table->index('user_id');
        });


        // ── transactions ──────────────────────────────────────────────────────
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();

            $table->timestamp('issued_at');
            $table->timestamp('due_date');
            $table->timestamp('returned_at')->nullable();

            $table->enum('status', ['active', 'overdue', 'returned'])->default('active');
            $table->timestamps();

            $table->index(['owner_id', 'status']);
            $table->index(['borrower_id', 'status']);
        });


        // ── borrow_requests ───────────────────────────────────────────────────
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();

            $table->date('proposed_pickup');
            $table->date('proposed_return');
            $table->text('message')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index(['resource_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('resources');
    }
};
