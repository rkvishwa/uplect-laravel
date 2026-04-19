<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_meeting_registrants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('zoom_meetings')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('zoom_registrant_id');
            $table->text('join_url');
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();

            $table->unique(['meeting_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_meeting_registrants');
    }
};
