<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->default('🏆');
            $table->integer('required_points')->default(0);
            $table->string('color')->default('#FFD700');
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('awarded_at');
            $table->timestamps();
            $table->unique(['user_id', 'badge_id']);
        });

        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->integer('total_points')->default(0);
            $table->integer('lending_count')->default(0);
            $table->integer('positive_reviews')->default(0);
            $table->integer('community_engagement')->default(0);
            $table->integer('rank')->default(0);
            $table->boolean('fraud_flag')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
