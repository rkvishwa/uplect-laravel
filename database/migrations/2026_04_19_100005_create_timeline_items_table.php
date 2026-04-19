<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->time('scheduled_start_time')->nullable();
            $table->time('scheduled_end_time')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->string('cardable_type');
            $table->unsignedBigInteger('cardable_id');
            $table->string('status', 32)->default('scheduled');
            $table->text('cancellation_reason')->nullable();
            $table->boolean('is_makeup')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_id', 'scheduled_date', 'order_index']);
            $table->index(['cardable_type', 'cardable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_items');
    }
};
