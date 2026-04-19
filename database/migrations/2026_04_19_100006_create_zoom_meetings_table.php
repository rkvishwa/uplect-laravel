<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_session_id')->unique()->constrained('course_sessions')->cascadeOnDelete();
            $table->string('zoom_meeting_id')->unique();
            $table->string('topic');
            $table->timestamp('start_at');
            $table->unsignedInteger('duration_minutes');
            $table->text('host_start_url');
            $table->text('shared_join_url')->nullable();
            $table->string('password')->nullable();
            $table->json('settings')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_meetings');
    }
};
