<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zoom_meetings', function (Blueprint $table) {
            $table->foreignId('zoom_account_id')
                ->nullable()
                ->after('course_session_id')
                ->constrained('zoom_accounts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('zoom_meetings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('zoom_account_id');
        });
    }
};
