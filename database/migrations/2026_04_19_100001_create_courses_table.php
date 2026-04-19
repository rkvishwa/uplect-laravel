<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('day_of_week', 16);
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('lecturer_payment_per_session_lkr', 10, 2)->default(0);
            $table->decimal('student_total_fee_lkr', 10, 2)->default(0);
            $table->string('status', 32)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'status']);
            $table->index('lecturer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
