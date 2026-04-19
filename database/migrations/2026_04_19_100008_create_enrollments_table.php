<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('payment_method', 32);
            $table->string('payment_status', 32)->default('pending');
            $table->string('payment_reference')->nullable();
            $table->string('bank_slip_path')->nullable();
            $table->decimal('amount_lkr', 10, 2)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamp('enrolled_at')->nullable();
            $table->string('status', 32)->default('pending');
            $table->timestamps();

            $table->index(['course_id', 'student_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
