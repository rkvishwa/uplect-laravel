<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('account_id');
            $table->string('client_id');
            $table->text('client_secret');
            $table->string('host_user_id');
            $table->string('timezone', 64)->default('Asia/Colombo');
            $table->boolean('waiting_room')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_accounts');
    }
};
